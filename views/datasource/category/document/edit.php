<script>
<?php if(!Acl::check($ds->type().$ds->id().'.document.edit')): ?>
$(function() {
	$('input,textarea,select').attr('disabled', 'disabled');
})
<?php endif; ?>
var API_FORM_ACTION = '/datasource/<?php echo $ds->type(); ?>-document.<?php if($doc->loaded()): ?>update<?php else: ?>create<?php endif; ?>'; 
</script>

<div class="outline">
	<div class="widget outline_inner">
	<?php if(Acl::check($ds->type().$ds->id().'.document.edit')): ?>
	<?php echo Form::open(Request::current()->url() . URL::query(array('id' => $doc->id)), array(
		'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
	)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	<?php echo Form::hidden('id', $doc->id); ?>
	<?php echo Form::hidden('csrf', Security::token()); ?>
	<?php else: ?>
	<div class="form-horizontal">
	<?php endif; ?>
	<div class="widget-title">
		<div class="control-group">
			<label class="control-label title"><?php echo __('Header'); ?></label>
			<div class="controls">
				<?php echo Form::input('header', $doc->header, array(
					'class' => 'input-title input-block-level slug-generator', 
					'data-slug' => '.from-header'
				)); ?>
			</div>
			
			<div class="controls">
				<?php echo View::factory('datasource/hybrid/document/fields/published', array(
					'doc' => $doc
				)); ?>
			</div>	
		</div>	
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label" for="slug"><?php echo __('Category slug'); ?></label>
			<div class="controls">
				<div class="row-fluid">
					<div class="input-append span10">
						<?php echo Form::input('slug', $doc->slug, array(
							'class' => 'input-xxlarge slug from-header', 
							'data-separator' => '-',
							'id' => 'slug'
						)); ?>
						<button name="copy_from_header" class="btn"><i class="icon-magnet"></i></button>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<div class="control-group">
			<label class="control-label" for="parent_id"><?php echo __('Parent category'); ?></label>
			<div class="controls">
				<?php echo Form::select('parent_id', $ds->sitemap()->exclude(array($doc->id), FALSE)->select_choices('header', TRUE, __('--- none ---')), $doc->parent_id); ?>
			</div>
		</div>
	</div>		
	<?php if(Acl::check($ds->type().$ds->id().'.document.edit')): ?>
	<div class="form-actions widget-footer">
		<?php echo UI::actions(TRUE, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		)) . URL::query(array('ds_id' => $ds->id()), FALSE)); ?>
	</div>
	<?php echo Form::close(); ?>
	<?php else: ?>
	</div>
	<?php endif; ?>
</div></div>