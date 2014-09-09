<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('datasource.headline.actions', function($ds) {
	if($ds->type() != 'category')
	{
		return;
	}
	
	echo View::factory('datasource/category/actions');
});