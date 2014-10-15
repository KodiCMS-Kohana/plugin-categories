<?php if (!$widget->ds_id): ?>
<div class="alert alert-warning alert-dark no-margin-b">
	<i class="fa fa-lightbulb-o fa-lg"></i> <?php echo __('You need select section'); ?>
</div>
<?php endif; ?>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-xs-3" for="ds_id"><?php echo __('Category section'); ?></label>
		<div class="col-xs-9">
			<?php echo Form::select('ds_id', Datasource_Data_Manager::get_all_as_options('category'), $widget->ds_id, array(
				'class' => 'form-control', 'id' => 'ds_id'
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
</div>

<?php if($widget->ds_id): ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<div class="col-xs-offset-3 col-xs-9">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('seo_information', 1, $widget->seo_information); ?> <?php echo __('Change meta headers'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('crumbs', 1, $widget->crumbs); ?> <?php echo __('Change bread crumbs'); ?></label>
		</div>
	</div>
	<hr />
	<div class="form-group">
		<label class="control-label col-md-3" for="related_widget"><?php echo __('Related widget'); ?></label>
		<div class="col-md-9">
			<?php
				$widgets = Widget_Manager::get_related(array('hybrid_headline'));

				if( ! empty($widgets) )
				{
					$widgets = array(__('--- Not set ---')) + $widgets;

					$selected = NULL;

					echo Form::select('widgets', $widgets, $widget->fetched_widgets); 
				}
			?>
		</div>
	</div>
</div>
<?php endif; ?>