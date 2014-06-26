<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Category_Hybrid extends Model_Widget_Category_Decorator {
	
	/**
	 *
	 * @var array 
	 */
	public $fields = array();
	
	/**
	 * Запрашивать количество документов в узлах
	 * @var boolean 
	 */
	public $count_documents = FALSE;

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->fields = array();
		
		parent::set_values($data);

		$this->count_documents = (bool) Arr::get($data, 'count_documents');
		return $this;
	}
	
	public function set_widgets($data = array())
	{
		if( ! is_array($data))
		{
			return;
		}
		
		foreach($data as $field_id => $row)
		{
			if(isset($row['widget_id']))
			{
				$this->fetched_widgets[(int) $field_id] = array(
					'ds_id' => (int) $row['ds_id'],
					'widget_id' => (int) $row['widget_id']
				);
			}
		}
		
		return $this->fetched_widgets;
	}

	protected function _load_related_widget( $ds_id )
	{
		$widget_id = $this->_widget_id_by_ds_id($ds_id);
		return parent::_load_related_widget($widget_id);
	}
	
	public function get_categories()
	{
		if( $this->_categories !== NULL)
		{
			return $this->_categories;
		}
		
		$this->_categories = array();
		
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
					$this->_categories[$field->from_ds] = array(
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
			->where('ds_id', 'in', array_unique($category_ids))
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
			$this->_categories[$row['ds_id']]['tree'][$id] = $row;
		}
		
		foreach($this->_categories as $ds_id => $sub_categories)
		{
			$this->_categories[$ds_id]['tree'] = $this->_build_tree($sub_categories['tree'], $ds_id);
		}
		
		return $this->_categories;
	}
}