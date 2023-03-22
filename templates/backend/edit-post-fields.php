<?php
/**
 * Edit post fields form in backend.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
}

wp_enqueue_style( 'elementor-frontend' );
add_filter( '_voxel/enqueue-custom-popup-kit', '__return_false' );
?>
<?php get_header() ?>
<style type="text/css">
	body {
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		background-color: #FFFFFF;
		--e-global-color-primary: #242C39;
		--e-global-color-secondary: #242C39;
		--e-global-color-text: #484E59;
		--e-global-color-accent: #EF2953;
		--ts-shade-1: #323436;
		--ts-shade-2: #323436;
		--ts-shade-3: #6e7174;
		--ts-shade-4: #e6e8e8;
		--ts-shade-5: #f3f3f3;
		--ts-shade-6: #f7f7f7;
		--ts-accent-1: var(--e-global-color-accent);
		--ts-accent-2: var(--e-global-color-accent);
		padding: 60px;
/*		min-height: 1000px;*/
		overflow-y: auto;
	}


	body .ts-form-group.ui-image-field img { width: 40%; }

	.ts-save-changes {
		display: none;
	}

	.elementor-widget-container {
		/* max-width: 640px;*/
		margin: auto;

	}

	.ts-form-group {
		width: 100%;
	}
	.ts-filter.ts-filled svg, .ts-filter.ts-filled i {
	    color: var(--ts-accent-1);
	    fill: var(--ts-accent-1);
	}

	.iframe-editor-vx {
		max-width: 600px;
		margin: auto;
		padding-bottom: 100px;
	}

	@media (max-width: 1024px) {


		.ts-popup-root .ts-popup-content-wrapper {
		    max-height: 360px !important;
		}

		.ts-field-popup-container .ts-field-popup {
			flex-direction: column !important;
		}
	}

	@media (max-width: 768px) {
		.ts-popup-root .ts-field-popup-container .ts-field-popup {
			background: #fff !important;
			border: 1px solid var(--ts-shade-4) !important;
			border-radius: 10px !important;
			width: 100% !important;
			left: 0 !important;
			top: 0 !important;
			min-width: 270px !important;
			overflow: hidden !important;
		}

		.ts-field-popup .ts-popup-controller {
		    padding: 10px 15px!important;
		    border-top: 1px solid!important;
		    border-color: var(--ts-shade-4)!important;
		    border-bottom: none !important;
		}

		.ts-popup-root .ts-field-popup-container {
			position: relative!important;
			z-index: 50!important;
			width: 100%!important;
			margin: 10px 0!important;
			backface-visibility: hidden!important;
			height: auto !important;
		}
	}

	/* title, description, and featured image fields are already present in the wp-admin edit post ui */
	.field-key-title, .field-key-description, .field-key-_thumbnail_id {
		display: none !important;
	}
</style>
<div data-elementor-type="wp-page" data-elementor-id="0" class="elementor elementor-0 iframe-editor-vx">
	<?php $widget->print_element() ?>
</div>

<?php get_footer() ?>