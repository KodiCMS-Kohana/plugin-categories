<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Category_Hybrid extends Model_Widget_Hybrid {
		
	/**
	 *
	 * @var bool 
	 */
	public $only_published = TRUE;
	
	/**
	 *
	 * @var array 
	 */
	public $categories = NULL;
	
	/**
	 *
	 * @var array 
	 */
	public $fields = array();
	
	/**
	 * Идентификатор узла
	 * @var string 
	 */
	public $category_id_ctx = 'category';
	
	/**
	 * Запрашивать количество документов в узлах
	 * @var boolean 
	 */
	public $count_documents = FALSE;
	
	/**
	 *
	 * @var string 
	 */
	public $docs_uri = NULL;

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		parent::set_values($data);
		$this->only_published = (bool) Arr::get($data, 'only_published');
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');
		$this->count_documents = (bool) Arr::get($data, 'count_documents');

		$this->docs_uri = Arr::get($data, 'docs_uri', $this->docs_uri);
		
		$this->deepness = (int) Arr::get($data, 'deepness');
		if($this->deepness < 1) $this->deepness = 1;

		return $this;
	}
	
	public function get_ds_category_fields()
	{
		if( ! $this->ds_id)
		{
			return array();
		}
		
		$datasource = Datasource_Section::load($this->ds_id);
		
		if($datasource === NULL)
		{
			return array();
		}
		
		$fields = array();
		foreach($datasource->record()->fields() as $field)
		{
			if($field->type != 'source_category') continue;
			
			$fields[$field->id] = $field->header;
		}
		
		return $fields;
	}

	/**
	 * 
	 * @return array
	 */
	public function fetch_data()
	{
		return array(
			'categories' => $this->get_categories()
		);
	}
	
	public function get_categories()
	{
		if( $this->categories !== NULL)
		{
			return $this->categories;
		}
		
		$this->categories = array();
		
		$datasource = Datasource_Section::load($this->ds_id);
		if($datasource === NULL)
		{
			return array();
		}
		
		$category_ids = array();
		$field_names = array();
		if( ! empty($this->fields) )
		{
			foreach($datasource->record()->fields() as $field)
			{
				if(in_array($field->id, $this->fields))
				{
					$category_ids[] = $field->from_ds;
					$this->categories[$field->from_ds] = array(
						'header' => $field->header,
						'tree' => array()
					);
				}
			}
		}
		
		
		$query = DB::select('id', 'parent_id', 'slug', 'header', 'published', 'ds_id')
			->from('dscategory')
			->join('dscategory_documents', 'left')
				->on('dscategory_documents.category_id', '=', 'dscategory.id')
			->where('ds_id', 'in', $category_ids)
			->order_by('parent_id')
			->order_by('position');
		
		if($this->count_documents)
		{
			$query
				->select(array(DB::expr('COUNT(document_id)'), 'total'))
				->group_by('category_id');
		}
		
		if($this->only_published)
		{
			$query->where('published', '=', 1);
		}
		
		foreach($query->execute()->as_array('id') as $id => $row)
		{
			$this->categories[$row['ds_id']]['tree'][$id] = $row;
		}
		
		foreach($this->categories as $ds_id => $sub_categories)
		{
			$this->categories[$ds_id]['tree'] = $this->_build_tree($sub_categories['tree']);
		}
		
		return $this->categories;
	}
	
	protected function _build_tree($categories)
	{
		$rebuild_array = array();
		foreach ($categories as &$row)
		{
			$row['level'] = 0;
			$row['published'] = (bool) $row['published'];
			
			if(!empty($this->category_id_ctx))
			{
				$row['href'] = URL::site($this->docs_uri . URL::query(array($this->category_id_ctx => $row['id'])));
				
				$category_id = (int) $this->_ctx->get($this->category_id_ctx);
				$row['is_active'] = $row['id'] === $category_id;
			}
			
			$rebuild_array[$row['parent_id']][] = &$row;
		}
		
		foreach ($categories as & $row)
		{
			if(isset($rebuild_array[$row['id']]))
			{
				foreach ($rebuild_array[$row['id']] as & $_row)
				{
					$_row['level'] = Arr::get($row, 'level', 0) + 1;
					$_row['parent'] = $row;
					$_row['slug'] = $row['slug'] . '/' . $_row['slug'];
				}
					
				$row['childs'] = $rebuild_array[$row['id']];
			}
		}
		
		if(!empty($rebuild_array))
		{
			$rebuild_array = reset($rebuild_array);
		}
		
		return new Sitemap($rebuild_array);
	}

	public function count_total()
	{
		return 1;
	}
	
	public function fetch_backend_content()
	{
		try
		{
			$content = View::factory( 'widgets/backend/' . $this->backend_template(), array(
					'widget' => $this
				))->set($this->backend_data());
		}
		catch( Kohana_Exception $e)
		{
			$content = NULL;
		}
		
		return $content;
	}
}