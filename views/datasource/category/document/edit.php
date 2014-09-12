<script>
<?php if(!$document->has_access_change()): ?>
$(function() {
	$('input,textarea,select').attr('disabled', 'disabled');
})
<?php endif; ?>
var API_FORM_ACTION = '/datasource/<?php echo $datasource->type(); ?>-document.<?php if($document->loaded()): ?>update<?php else: ?>create<?php endif; ?>'; 
</script>

<?php if($document->has_access_change()): ?>
<?php echo Form::open(Route::get('datasources')->uri(array(
		'controller' => 'document',
		'directory' => $datasource->type(),
		'action' => 'post'
	)), array(
	'class' => 'form-horizontal panel', 'enctype' => 'multipart/form-data'
)); ?>
<?php echo Form::hidden('ds_id', $datasource->id()); ?>
<?php echo Form::hidden('id', $document->id); ?>
<?php echo Form::token(); ?>
<?php else: ?>
<div class="form-horizontal panel">
<?php endif; ?>
	<div class="panel-heading">
		<div class="form-group form-group-lg">
			<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo __('Header'); ?></label>
			<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
				<?php echo Form::input('header', $document->header, array(
					'class' => 'form-control slug-generator', 'data-slug' => '.from-header'
				)); ?>
			</div>

			<?php echo View::factory('datasource/hybrid/document/fields/published'); ?>
		</div>	
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="slug"><?php echo __('Category slug'); ?></label>
			<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
				<div class="input-group">
					<?php echo Form::input('slug', $document->slug, array(
						'class' => 'form-control slug from-header', 
						'data-separator' => '-',
						'id' => 'slug'
					)); ?>
					<div class="input-group-btn">
						<button name="copy_from_header" class="btn btn-default"><i class="fa fa-magnet"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group form-inline">
			<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="parent_id"><?php echo __('Parent category'); ?></label>
			<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
				<?php echo Form::select('parent_id', $datasource->sitemap()->exclude(array($document->id), FALSE)->select_choices('header', TRUE, __('--- none ---')), $document->parent_id); ?>
			</div>
		</div>
	</div>		
	<?php if($document->has_access_change()): ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions(TRUE, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		)) . URL::query(array('ds_id' => $datasource->id()), FALSE)); ?>
	</div>
<?php echo Form::close(); ?>
<?php else: ?>
</div>
<?php endif; ?>