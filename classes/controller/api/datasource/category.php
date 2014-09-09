<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Api_Datasource_category extends Controller_System_API
{
	public function section($ds_id)
	{
		$ds = Datasource_Data_Manager::load((int) $ds_id);
		
		if(empty($ds))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Datasource section not found');
		}
		
		return $ds;
	}

	public function rest_get()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		$ds = $this->section($ds_id);
		
		$this->response((string) View::factory('datasource/category/children', array(
			'level' => 0,
			'data' => $ds->sitemap()->as_array(),
			'datasource' => $ds
		)));
		
	}
	
	public function get_sort()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		$ds = $this->section($ds_id);
		
		$this->response((string) View::factory( 'datasource/category/sort', array(
			'categories' => $ds->sitemap()->as_array()
		)));
	}
	
	public function post_sort()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		$ds = $this->section($ds_id);
		
		$categories = $this->param('categories', array(), TRUE);
		
		$ds->sort($categories);
	}
}