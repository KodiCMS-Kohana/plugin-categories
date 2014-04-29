<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Category
 */
class DataSource_Section_Category extends Datasource_Section {
	
	/**
	 * Таблица раздела
	 * 
	 * @var string
	 */
	protected $_ds_table = 'dscategory';
	
	/**
	 * Тип раздела
	 * 
	 * @var string
	 */
	protected $_type = 'category';
	
	/**
	 *
	 * @var Sitemap 
	 */
	protected $_categories = NULL;


	/**
	 * return Sitemap
	 */
	public function sitemap()
	{
		if($this->_categories !== NULL)
		{
			return $this->_categories;
		}

		$categories = DB::select('id', 'parent_id', 'slug', 'header', 'published')
			->from($this->table())
			->where('ds_id', '=', $this->id())
			->order_by('parent_id')
			->order_by('position')
			->execute()
			->as_array('id');
		
		$rebuild_array = array();
		foreach ($categories as &$row)
		{
			$row['level'] = 0;
			$row['published'] = (bool) $row['published'];
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

		$this->_categories = new Sitemap(reset($rebuild_array));

		return $this->_categories;
	}
	
	/**
	 * 
	 * @param array $categories
	 */
	public function sort(array $categories)
	{		
		if( count( $categories ) > 0)
		{
			$insert = DB::insert($this->table())->columns(array('id', 'parent_id', 'position', 'ds_id'));

			foreach ($categories as $cat)
			{
				$insert
					->values(array((int) $cat['id'], (int) $cat['parent_id'], (int) $cat['position'], (int) $this->id()));
			}
			
			$insert = $insert . ' ON DUPLICATE KEY UPDATE parent_id = VALUES(parent_id), position = VALUES(position)';
		
			DB::query(Database::INSERT, $insert)->execute();
		}
	}
	
	protected function _serialize()
	{
		$vars = parent::_serialize();
		unset($vars['_categories']);
		
		return $vars;
	}
}