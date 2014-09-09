 <div class="sort-pages">
	<div class="dd">
		<?php echo recurse_sort_categories($categories); ?>
	</div>
</div>
<?php function recurse_sort_categories(array $childs) {
	$data = '';
	if(empty($childs)) return $data;
	
	$data = '<ul class="dd-list list-unstyled">';
	foreach ($childs as $category)
	{
		$data .= (string) View::factory('datasource/category/sortitem', array(
			'category' => $category,
			'childs' => !empty($category['childs']) ? recurse_sort_categories($category['childs']) : ''
		));
	}
	
	$data .= '</ul>';
	
	return $data;
} ?>