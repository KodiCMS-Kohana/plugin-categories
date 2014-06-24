<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php
$fields = $widget->get_ds_category_fields();
?>
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
		<label class="control-label" for="docs_uri"><?php echo __('Documents page (URI)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'docs_uri', $widget->docs_uri, array(
				'class' => 'input-xlarge', 'id' => 'docs_uri'
			) ); ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="category_id_ctx"><?php echo __('Category ID (Ctx)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'category_id_ctx', $widget->category_id_ctx, array(
				'class' => 'input-small', 'id' => 'category_id_ctx'
			) ); ?>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('count_documents', 1, $widget->count_documents); ?> <?php echo __('Request number of documents in nodes'); ?></label>
		</div>
	</div>
</div>

<div class="widget-header">
	<h4><?php echo __('Fields that used to form hierarchy'); ?></h4>
</div>
<div class="widget-content">
	<?php if(!empty($fields)): ?>
	<?php echo Form::select('fields[]', $fields, (array) $widget->fields, array(
		'class' => 'input-block-level'
	)); ?>
	<?php else: ?>
	<div class="alert alert-warning">
		<i class="icon icon-lightbulb"></i> <?php echo __('No category fields found'); ?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>