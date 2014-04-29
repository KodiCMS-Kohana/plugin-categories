<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="ds_id"><?php echo __('Category section'); ?></label>
		<div class="controls">
			<?php echo Form::select( 'ds_id', $widget->options(), $widget->ds_id, array(
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
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
		</div>
	</div>
</div>
<?php endif; ?>