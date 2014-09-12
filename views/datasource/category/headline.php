<table id="CategoriesHeadlineHeader" class="table no-margin-b">
	<thead>
		<tr>
			<th class="col-xs-8"><?php echo __('Category'); ?></th>
			<th class="col-xs-4"><?php echo __('URI'); ?></th>
		</tr>
	</thead>
</table>
<div class="tree-items" id="CategoriesHeadline">
<?php echo View::factory('datasource/category/children', array(
	'level' => 0,
	'data' => $data,
	'datasource' => $datasource
)); ?>
</div>
<div id="CategoriesReorderContaier"></div>