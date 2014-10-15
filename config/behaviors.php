<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'hybrid_docs' => array(
		'routes' => array(
			'/category/<category_id>' => array(
				'regex' => array(
					'category_id' => '[0-9\,]+'
				),
				'method' => 'stub'
			),
			'/category/<category_slug>' => array(
				'regex' => array(
					'category_slug' => '[a-z0-9\_\-\,]+'
				),
				'method' => 'stub'
			),
		)
	),
);