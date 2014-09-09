<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Category
 */
class Datasource_Section_Category_Headline extends Datasource_Section_Headline {

	public function get( array $ids = NULL )
	{
		Assets_Package::load('nestable');
		return $this->_section->sitemap()->as_array();
	}
	
	public function count_total( array $ids = NULL ) { return 0; }
}