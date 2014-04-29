<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Hybrid
 */
class DataSource_Category_Document extends Datasource_Document {
	
	protected $_system_fields = array(
		'id' => NULL,
		'ds_id' => 0,
		'parent_id' => 0,
		'slug' => NULL,
		'published' => NULL,
		'header' => NULL
	);
	
	public function filters()
	{
		$filters = parent::filters();
		
		$filters['slug'] = array(
			array('URL::title')
		);
		
		$filters['parent_id'] = array(
			array('intval')
		);
				
		return $filters;
	}
	
	public function rules()
	{
		$rules = parent::rules();
		
		$rules['slug'] = array(
			array('not_empty')
		);

		return $rules;
	}
}