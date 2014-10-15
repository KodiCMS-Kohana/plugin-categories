<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>
<?php if( ! $widget->ds_id ): ?>
<div class="alert alert-warning alert-dark no-margin-b">
	<i class="fa fa-lightbulb-o fa-lg"></i> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-xs-3" for="docs_uri"><?php echo __('Documents page (URI)'); ?></label>
		<div class="col-xs-9">
			<?php echo Form::input('docs_uri', $widget->docs_uri, array(
				'class' => 'form-control', 'id' => 'docs_uri'
			)); ?>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-xs-3" for="category_id_ctx"><?php echo __('Category ID (Ctx)'); ?></label>
		<div class="col-xs-9">
			<?php echo Form::input('category_id_ctx', $widget->category_id_ctx, array(
				'class' => 'form-control', 'id' => 'category_id_ctx'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-xs-3" for="category_field"><?php echo __('Category ID field'); ?></label>
		<div class="col-xs-3">
			<?php echo Form::select('category_field', array('id' => 'ID', 'slug' => 'Slug'), $widget->category_field, array(
				'class' => 'form-control', 'id' => 'category_field'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-xs-offset-3 col-xs-9">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('count_documents', 1, $widget->count_documents); ?> <?php echo __('Request number of documents in nodes'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('seo_information', 1, $widget->seo_information); ?> <?php echo __('Change meta headers'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('crumbs', 1, $widget->crumbs); ?> <?php echo __('Change bread crumbs'); ?></label>
		</div>
	</div>
</div>

<?php $fields = DataSource_Hybrid_Field_Factory::get_section_fields($widget->ds_id, array('source_category')); ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Fields that used to form hierarchy'); ?></span>
</div>
<div class="widget-content">
	<?php if(!empty($fields)): ?>
	<table id="section-fields" class="table table-striped">
		<colgroup>
			<col width="30px" />
			<col width="100px" />
			<col width="200px" />
			<col />
		</colgroup>
		<tbody>			
			<?php foreach($fields as $field): ?>
			<tr id="field-<?php echo $field->name; ?>">
				<td class="f">
					<?php echo Form::hidden('widgets['.$field->id.'][ds_id]', $field->from_ds); ?>
					<?php echo Form::checkbox('fields[]', $field->id, in_array($field->id, $widget->fields), array(
						'id' => 'ch' . $field->id
					)); ?>
				</td>
				<td class="sys">
					<?php echo Form::label('ch' . $field->id, $field->key); ?>
				</td>
				<td>
					<?php echo HTML::anchor('/backend/hybrid/field/edit/' . $field->id, $field->header, array('target' => '_blank', 'class' => 'popup fancybox.iframe') ); ?>
				</td>
				<td>
					<?php
						$types = $field->widget_types();
						if($types !== NULL)
						{
							$widgets = Widget_Manager::get_related($field->widget_types(), $widget->ds_id);

							if(isset($widgets[$widget->id])) unset($widgets[$widget->id]);

							if( ! empty($widgets) )
							{
								$widgets = array(__('--- Not set ---')) + $widgets;

								$selected = NULL;

								if(isset($widget->fetched_widgets[$field->id]))
								{
									$selected = $widget->fetched_widgets[$field->id]['widget_id'];
								}

								echo Form::select('widgets['.$field->id.'][widget_id]', $widgets, $selected); 
							}
						}
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<div class="alert alert-warning alert-dark no-margin-b">
		<i class="fa fa-lightbulb-o fa-lg"></i> <?php echo __('No category fields found'); ?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>