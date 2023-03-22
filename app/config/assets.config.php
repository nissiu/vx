<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

return [
	'styles' => [
		'backend.css',
		'elementor.css',
		'action.css',
		'commons.css',
		'create-post.css',
		'gallery.css',
		'login.css',
		'nav-menu.css',
		'orders.css',
		'post-feed.css',
		'pricing-plan.css',
		'product-form.css',
		'quick-search.css',
		'review-stats.css',
		'ring-chart.css',
		'search-form.css',
		'social-feed.css',
		'user-area.css',
		'work-hours.css',
		'popup-kit.css',
		'map.css',
		'mapbox.css',
		'bar-chart.css',
		'forms.css',
		'preview.css',
		'messages.css',
	],

	'scripts' => [
		'backend.js',
		'dynamic-tags.js',
		'elementor.js',
		'membership-editor.js',
		'app-events.js',
		'template-manager.js',
		'post-type-editor.js',
		'product-type-editor.js',
		'general-settings.js',
		'onboarding.js',
		[ 'src' => 'commons.js', 'deps' => [ 'vue' ] ],
		[ 'src' => 'auth.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'create-post.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'google-maps.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'mapbox.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'orders.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'notifications.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'messages.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'product-form.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'search-form.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'timeline.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'reservations.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'vendor-stats.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'quick-search.js', 'deps' => [ 'vx:commons.js' ] ],
		[ 'src' => 'collections.js', 'deps' => [ 'vx:commons.js' ] ],
	],
];
