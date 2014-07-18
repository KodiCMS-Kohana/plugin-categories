<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="ds_id"><?php echo __('Category section'); ?></label>
		<div class="controls">
			<?php echo Form::select( 'ds_id', Datasource_Data_Manager::get_all_as_options('category'), $widget->ds_id, array(
				'class' => 'input-large', 'id' => 'ds_id'
			) ); ?>
		</div>
	</div>
</div>

<?php if( ! $widget->ds_id ): ?>
<div class="widget-content">
	<div class="alert alert-warning">
		<i class="icon icon-lightbulb"></i> <?php echo __('You need select hybrid section'); ?>
	</div>
</div>
<?php else: ?>
<div class="widget-header">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('seo_information', 1, $widget->seo_information); ?> <?php echo __('Change meta headers'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('crumbs', 1, $widget->crumbs); ?> <?php echo __('Change bread crumbs'); ?></label>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="related_widget"><?php echo __('Related widget'); ?></label>
		<div class="controls">
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