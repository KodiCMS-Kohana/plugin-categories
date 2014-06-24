<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_Category extends DataSource_Hybrid_Field_Source {

	protected $_props = array(
		'isreq' => TRUE,
		'is_array' => FALSE
	);
	
	protected $_category_id = 0;

	public function get_type()
	{
		return ($this->is_array === TRUE) 
			? 'VARCHAR(255)' 
			: 'INT(11) UNSIGNED';
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
		$old_categories = $old->get($this->name);
		$new_categories = $new->get($this->name);
		
		$o = empty($old_categories) ? array() : explode(',', $old_categories);
		$n = empty($new_categories) ? array() : explode(',', $new_categories);
		
		$this->remove_values($old->id, array_diff($o, $n));
		$this->add_values($old->id, array_diff($n, $o));
	}
	
	public function add_values($document_id, array $values) 
	{
		if( ! empty($values) ) 
		{
			$insert = DB::insert('dscategory_documents')
				->columns(array(
					'document_id', 'field_id', 'category_id'
				));
			
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
		if( ! empty($values)) 
		{
			DB::delete('dscategory_documents')
				->where('field_id', '=', $this->id)
				->where('document_id', '=', $document_id)
				->where('category_id', 'in', $values)
				->execute();
		}
	}
	
	public static function fetch_widget_field( $widget, $field, $row, $fid, $recurse )
	{
		return $row[$fid];
	}

	public function get_query_props(\Database_Query $query)
	{
		$query
			->join(array('dscategory_documents', 'dscd' . $this->id), 'left')
				->on('d.id', '=', 'dscd' . $this->id . '.document_id');
		
		$node = Context::instance()->get('category_node');
		if($node !== NULL)
		{
			$query
				->where('dscd' . $this->id.'.category_id', '=', (int) $node);
		}

		return $query;
	}
}