<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Model_Widget_Category_Decorator extends Model_Widget_Decorator {
	
	/**
	 *
	 * @var bool 
	 */
	public $only_published = TRUE;
	
	/**
	 *
	 * @var array 
	 */
	protected $_categories = NULL;
	
	/**
	 *
	 * @var array 
	 */
	public $fetched_widgets = array();
	
	/**
	 * Идентификатор узла
	 * @var string 
	 */
	public $category_id_ctx = 'category';
	
	/**
	 *
	 * @var string 
	 */
	public $docs_uri = NULL;
	
	/**
	 *
	 * @var stdClass 
	 */
	protected $_current_category = NULL;

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->fetched_widgets = array();

		parent::set_values($data);
		$this->only_published = (bool) Arr::get($data, 'only_published');
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');
		$this->docs_uri = Arr::get($data, 'docs_uri', $this->docs_uri);
		$this->seo_information = (bool) Arr::get($data, 'seo_information');
		$this->crumbs = (bool) Arr::get($data, 'crumbs');

		return $this;
	}
	
	protected function _load_category()
	{
		if($this->_current_category === NULL)
		{
			$category_id = (int) $this->_ctx->get($this->category_id_ctx);
			$section = Datasource_Section::factory('category');

			$this->_current_category = DB::select()
				->from($section->table())
				->where('id', '=', $category_id)
				->as_object()
				->limit(1)
				->execute()
				->current();
		}
		
		return $this->_current_category;
	}

	protected function _load_related_widget( $widget_id )
	{
		if( empty($widget_id) ) return NULL;

		$widget = Context::instance()->get_widget($widget_id);
		
		if( ! $widget)
		{
			$widget = Widget_Manager::load($widget_id);
		}
		
		if($widget === NULL) return NULL;
		
		return $widget;
	}
	
	protected function _widget_id_by_ds_id($ds_id)
	{
		foreach ($this->fetched_widgets as $row)
		{
			if($row['ds_id'] == $ds_id)
			{
				return $row['widget_id'];
			}
		}
		
		return NULL;
	}
	
	public function on_page_load()
	{
		parent::on_page_load();
		
		if ( ! empty($this->category_id_ctx) AND $this->seo_information === TRUE)
		{
			$category = $this->_load_category();
			
			if ($category !== NULL)
			{
				$this->_ctx
					->set('category.header', $category->header)
					->set('category.description', $category->description);
			}
		}
	}
	
	public function change_crumbs( Breadcrumbs &$crumbs )
	{
		parent::change_crumbs( $crumbs );
		$page = $this->_ctx->get_page();
		$category = $this->_load_category();
		
		$crumb = $crumbs->get_by('url', $page->url);
		
		if ($crumb !== NULL)
		{
			$crumb->active = FALSE;
			$crumbs->add($category->header, FALSE, TRUE);
		}
	}

	/**
	 * 
	 * @param array $categories
	 * @param integer $ds_id
	 * @return \Sitemap
	 */
	protected function _build_tree( array $categories, $widget_id)
	{
		$widget = $this->_load_related_widget($widget_id);
		$rebuild_array = array();
		foreach ($categories as &$row)
		{
			$row['level'] = 0;
			$row['published'] = (bool) $row['published'];
			
			if(($widget !== NULL AND $this->count_documents !== TRUE) OR ($this->count_documents AND $widget !== NULL AND $row['total'] > 0))
			{
				Context::instance()->set('category_node_' . $widget->ds_id, $row['id']);
				$row['docs'] = $widget->reset()->get_documents();
			}
			else if($widget !== NULL)
			{
				$row['docs'] = array();
			}
			
			$row['href'] = NULL;
			$row['is_active'] = FALSE;

			if ( ! empty($this->category_id_ctx))
			{
				$row['href'] = URL::site($this->docs_uri . URL::query(array($this->category_id_ctx => $row['id'])));
				
				$category_id = (int) $this->_ctx->get($this->category_id_ctx);
				$row['is_active'] = $row['id'] == $category_id;
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

	/**
	 * 
	 * @return array
	 */
	public function fetch_data()
	{
		if( ! $this->ds_id )
		{
			return array();
		}
		
		$this->get_categories();
		
		if(empty($this->_categories) AND $this->throw_404)
		{
			$this->_ctx->throw_404();
		}
		
		return array(
			'categories' => $this->_categories
		);
	}
	
	/**
	 * return array
	 */
	abstract public function get_categories();
}