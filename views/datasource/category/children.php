<ul class="unstyled" data-level="<?php echo $level; ?>">
	<?php foreach($data as $category): ?>
	<li data-id="<?php echo $category['id']; ?>">
		<div class="item">
			<div class="row-fluid">
				<div class="title span12">
					<?php echo HTML::anchor( Route::url('datasources', array(
						'controller' => 'document',
						'directory' => $section->type(),
						'action' => 'view'
					)) . URL::query(array(
						'ds_id' => $section->id(), 'id' => $category['id']
					)), $category['header'] ); ?>
				</div>
			</div>
		</div>

		<?php if(!empty($category['childs'])): ?>
		<?php echo View::factory('datasource/category/children', array(
			'level' => $level + 1,
			'data' => $category['childs'],
			'section' => $section
		)); ?>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>