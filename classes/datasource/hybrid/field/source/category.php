<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_Category extends DataSource_Hybrid_Field_Source {

	protected $_props = array(
		'isreq' => TRUE,
		'is_array' => FALSE
	);
	
	protected $_widget_types = array('hybrid_headline');
	protected $_category_id = 0;
	protected $_joined = FALSE;

	public function get_type()
	{
		return ($this->is_array === TRUE) 
			? 'VARCHAR(255)' 
			: 'INT(11) UNSIGNED';
	}
	
	public function sections()
	{
		$sections = array(__('--- Not set ---'));
		foreach (Datasource_Data_Manager::get_all($this->family) as $id => $section)
		{
			$sections[$id] = $section->name;
		}
		
		return $sections;
	}
	
	public function options()
	{
		$ds = Datasource_Data_Manager::load($this->from_ds);
		if($ds === NULL)
		{
			return array();
		}

		return $ds->sitemap()->select_choices('header', TRUE, __('--- Not set ---'));
	}
	
	public function remove()
	{
		parent::remove();
		
		DB::delete('dscategory_documents')
			->where('field_id', '=', $this->id)
			->execute();
	}
	
	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		$values = $doc->get($this->name);
		if(!is_array($values)) $values = array($values);
		
		$this->add_values($doc->id, $values);
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$old_categories = $this->get_values($new->id);
		$new_categories = $new->get($this->name);
		$new_categories = empty($new_categories) ? array() : explode(',', $new_categories);

		$this->remove_values($old->id, array_diff($old_categories, $new_categories));
		$this->add_values($old->id, array_diff($new_categories, $old_categories));
	}
	
	public function get_values($document_id)
	{
		return DB::select('category_id')
			->from('dscategory_documents')
			->where('field_id', '=', $this->id)
			->where('document_id', '=', $document_id)
			->execute()
			->as_array(NULL, 'category_id');
	}

	public function add_values($document_id, array $values) 
	{
		if (!empty($values))
		{
			$insert = DB::insert('dscategory_documents')
				->columns(array('document_id', 'field_id', 'category_id'));

			foreach ($values as $category_id)
			{
				$insert->values(array(
					$document_id, $this->id, $category_id
				));
			}

			$insert->execute();
		}
	}
	
	public function remove_values($document_id, array $values) 
	{
		if (!empty($values))
		{
			return (bool) DB::delete('dscategory_documents')
				->where('field_id', '=', $this->id)
				->where('document_id', '=', $document_id)
				->where('category_id', 'in', $values)
				->execute();
		}
		
		return FALSE;
	}
	
	public function fetch_headline_value( $value, $document_id )
	{
		if(empty($value))
		{
			return parent::fetch_headline_value($value, $document_id);
		}

		$category = DataSource_Hybrid_Field_Utils::get_document_header($this->from_ds, $value);
		
		if(!empty($category))
		{
			return HTML::anchor(Route::get('datasources')->uri(array(
					'directory' => 'category',
					'controller' => 'document',
					'action' => 'view'
				)) . URL::query(array('ds_id' => $this->from_ds, 'id' => $value), FALSE),
				$category,
				array(
					'class' => ' popup fancybox.iframe'
				)
			);
		}
		
		return parent::fetch_headline_value($value, $document_id);
	}
	
	public function filter_condition(Database_Query $query, $condition, $value, array $params = NULL)
	{
		$field_name = 'category_id';
		
		if(!empty($params['field']))
		{
			$field_name = $params['field'];
		}

		$query = $this->_join_table($query);
		
		if($field_name == 'slug' OR $field_name == 'header')
		{
			$query
				->join(array('dscategory', $this->_join_table_name('c')), 'left')
				->on($this->_join_table_name('c') . '.id', '=', $this->_join_table_name() . '.category_id')
				->where($this->_join_table_name('c') . '.' . $field_name, $condition, $value);
		}
		else
		{
			$query->where($this->_join_table_name() . '.' . $field_name, $condition, $value);
		}
	}
	
	public function get_query_props(\Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		$this->_joined = FALSE;
		$this
			->_join_table($query)
			->select(array($this->_join_table_name() . '.category_id', $this->id));
	
		$node = Context::instance()->get('category_node_' . $this->from_ds);

		if($node !== NULL)
		{
			$query->where($this->_join_table_name() . '.category_id', '=', (int) $node);
		}
	}
	
	protected function _join_table(Database_Query $query)
	{
		if($this->_joined === TRUE)
		{
			return $query;
		}
		
		$this->_joined = TRUE;

		return $query
			->join(array('dscategory_documents', $this->_join_table_name()), 'left')
			->on('d.id', '=', $this->_join_table_name() . '.document_id');
	}
	
	protected function _join_table_name($preffix = NULL)
	{
		return 'dscd' . $this->id . $preffix;
	}
}