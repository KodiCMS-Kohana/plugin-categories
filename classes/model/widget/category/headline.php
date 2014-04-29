<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Category_Headline extends Model_Widget_Decorator {
		
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
	 * @return array
	 */
	public function options()
	{
		$datasources = Datasource_Data_Manager::get_all('category');
		
		$options = array(__('--- Not set ---'));
		foreach ($datasources as $value)
		{
			$options[$value['id']] = $value['name'];
		}

		return $options;
	}

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		parent::set_values($data);
		$this->only_published = (bool) Arr::get($data, 'only_published');
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');

		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function fetch_data()
	{
		if( ! $this->ds_id ) return array();
		
		$this->get_categories();
		
		if(empty($this->categories) AND $this->throw_404)
		{
			$this->_ctx->throw_404();
		}
		
		return array(
			'categories' => $this->categories
		);
	}
	
	/**
	 * @return array
	 */
	public function get_categories()
	{
		if( $this->categories !== NULL ) return $this->categories;

		$ds = Datasource_Data_Manager::load($this->ds_id);
		
		$this->categories = $ds->sitemap();
		
		if($this->only_published === TRUE)
		{
			$this->categories->filter('published', FALSE);
		}

		return $this->categories;
	}
}