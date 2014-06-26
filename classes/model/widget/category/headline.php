<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Category_Headline extends Model_Widget_Category_Decorator {

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->fetched_widgets = NULL;
		
		parent::set_values($data);
		$this->only_published = (bool) Arr::get($data, 'only_published');
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');

		return $this;
	}
	
	public function set_widgets($data = array())
	{
		$this->fetched_widgets = (int) $data;

		return $this->fetched_widgets;
	}	
	
	/**
	 * @return array
	 */
	public function get_categories()
	{
		if( $this->_categories !== NULL)
		{
			return $this->_categories;
		}

		$ds = Datasource_Data_Manager::load($this->ds_id);
		
		$categories = $ds->get_query($this->only_published)->as_array('id');
		
		$this->_categories = $this->_build_tree($categories, $this->fetched_widgets);
		return $this->categories;
	}
}