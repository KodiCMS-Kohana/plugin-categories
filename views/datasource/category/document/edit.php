<script>
<?php if(!$ds->has_access('document.edit')): ?>
$(function() {
	$('input,textarea,select').attr('disabled', 'disabled');
})
<?php endif; ?>
var API_FORM_ACTION = '/datasource/<?php echo $ds->type(); ?>-document.<?php if($doc->loaded()): ?>update<?php else: ?>create<?php endif; ?>'; 
</script>

<?php if($ds->has_access('document.edit')): ?>
<?php echo Form::open(Route::get('datasources')->uri(array(
		'controller' => 'document',
		'directory' => $ds->type(),
		'action' => 'post'
	)), array(
	'class' => 'form-horizontal panel', 'enctype' => 'multipart/form-data'
)); ?>
<?php echo Form::hidden('ds_id', $ds->id()); ?>
<?php echo Form::hidden('id', $doc->id); ?>
<?php echo Form::token(); ?>
<?php else: ?>
<div class="form-horizontal panel">
<?php endif; ?>
	<div class="panel-heading">
		<div class="form-group form-group-lg">
			<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo __('Header'); ?></label>
			<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
				<?php echo Form::input('header', $doc->header, array(
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
					<?php echo Form::input('slug', $doc->slug, array(
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
				<?php echo Form::select('parent_id', $ds->sitemap()->exclude(array($doc->id), FALSE)->select_choices('header', TRUE, __('--- none ---')), $doc->parent_id); ?>
			</div>
		</div>
	</div>		
	<?php if($ds->has_access('document.edit')): ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions(TRUE, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		)) . URL::query(array('ds_id' => $ds->id()), FALSE)); ?>
	</div>
<?php echo Form::close(); ?>
<?php else: ?>
</div>
<?php endif; ?>