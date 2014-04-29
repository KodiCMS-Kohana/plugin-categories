<?php echo debug::vars($header); ?>

<h3>As array</h3>
<?php 
$array = clone($categories);
echo debug::vars($array->as_array()); 
?>

<h3>As flatten array</h3>
<?php 
$array = clone($categories);
echo debug::vars($array->flatten()); 
?>

<h3>Breadcrumbs</h3>
<?php 
$array = clone($categories);
echo debug::vars($array->find(4)->breadcrumbs()); 
?>

<h3>HTML Select</h3>
<?php 
$array = clone($categories);
echo Form::select('categories', $array->select_choices('header')); 
?>

<h3>Find element</h3>
<?php 
$array = clone($categories);
echo debug::vars($array->find_by('slug', 'news')->as_array(FALSE)); 
?>