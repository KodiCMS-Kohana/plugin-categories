<ul data-level="<?php echo $level; ?>" class="list-unstyled">
	<?php foreach($data as $category): ?>
	<li data-id="<?php echo $category['id']; ?>" class="<?php echo !$category['published'] ? 'unpublished' : ''; ?>">
		<div class="tree-item">
			<div class="row">
				<div class="title col-xs-8">
					<?php if ($datasource->has_access('document.edit')): ?>
					<span class="row-checkbox"><?php echo Form::checkbox('doc[]', $category['id'], NULL, array('class' => 'doc-checkbox')); ?></span>&nbsp;&nbsp;
					<?php endif; ?>
					<strong>
					<?php echo HTML::anchor($category->edit_link(), $category->header); ?>
					</strong>
				</div>
				<div class="slug col-xs-4 text-muted">
					<?php echo $category['uri']; ?>
				</div>
			</div>
		</div>

		<?php if(count($category['childs']) > 0): ?>
		<?php echo View::factory('datasource/category/children', array(
			'level' => $level + 1,
			'data' => $category['childs'],
			'datasource' => $datasource
		)); ?>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>