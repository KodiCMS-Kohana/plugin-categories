<?php defined('SYSPATH') or die('No direct access allowed.');

$categories = array(
	'category_headline' => __('Categories Headline')
);

if(Plugins::is_activated('hybrid'))
{
	$categories['category_hybrid'] = __('Categories for Hybrid documents');
}
return array(
	__('Categories') => $categories
);