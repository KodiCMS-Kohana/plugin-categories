<?php 
$categories = array(__('--- Not set ---'));
foreach ( Datasource_Data_Manager::get_all('category') as $id => $section )
{
	$categories[$id] = $section->name;
}
?>
<div class="form-group">
	<label class="control-label col-md-3" for="from_ds"><?php echo __('Datasource'); ?></label>
	<div class="col-md-3">
		<?php echo Form::select( 'from_ds', $categories, $field->from_ds); ?>
	</div>
</div>