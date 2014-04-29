<div class="widget widget-nopad">
	<div class="widget-content">
		<table id="CategoriesHeadlineHeader" class="table map-header">
			<colgroup>
				<col />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Category'); ?></th>
				</tr>
			</thead>
		</table>
		<div class="map-items" id="CategoriesHeadline">
		<?php echo View::factory('datasource/category/children', array(
			'level' => 0,
			'data' => $data,
			'section' => $section
		)); ?>
		</div>
		<div id="CategoriesReorderContaier"></div>
	</div>
</div>