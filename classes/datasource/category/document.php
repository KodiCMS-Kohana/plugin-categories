<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Hybrid
 */
class DataSource_Category_Document extends Datasource_Document {
	
	/**
	 *
	 * @var DataSource_Category_Document 
	 */
	protected $_parent = NULL;
	
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

	public function remove()
	{
		DB::delete('dscategory_documents')
			->where('category_id', '=', $this->id)
			->execute();

		return parent::remove();
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_uri()
	{
		if ($this->parent() !== NULL)
		{
			$result = $this->parent()->get_uri() . '/' . $this->slug;
		}
		else
		{
			$result = $this->slug;
		}

		return $result;
	}
	
	public function parent()
	{
		if($this->_parent === NULL AND $this->parent_id > 0)
		{
			if($this->parent instanceof DataSource_Category_Document)
			{
				$this->_parent = $this->parent;
			}
			else
			{
				$this->_parent = $this->section()->get_document($this->parent_id);
			}
		}
		
		return $this->_parent;
	}
}