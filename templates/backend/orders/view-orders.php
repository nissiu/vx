<div class="wrap">
	<h1><?= get_admin_page_title() ?></h1>
	<form class="vx-orders" method="get">
		<input type="hidden" name="status" value="<?= esc_attr( $_REQUEST['status'] ?? '' ) ?>" />
		<input type="hidden" name="page" value="<?= esc_attr( $_REQUEST['page'] ) ?>" />
		<?php $table->views() ?>
		<?php $table->display() ?>
	</form>
</div>

<style type="text/css">
	.column-customer img, .column-vendor img, .column-post img {
		margin-right: 10px;
		border-radius: 50px;
		display: inline-block;
		vertical-align: middle;
	}

	.column-post img {
		width: 32px;
		height: 32px;
	}

	#search-search-input {
		width: 250px;
	}

	.column-amount .price-amount {
		font-size: 16px;
	}

	#the-list td {
		vertical-align: middle;
	}

	#id, .column-id  { width: 50px; }

	.item-title {
		font-weight: 600;
		vertical-align: middle;
	}

	.column-details, #details { width: 66px; }
</style>

<script type="text/javascript">
	jQuery( $ => {
		if ( window.matchMedia('screen and (max-width: 782px)').matches ) {
			$('.vx-orders tr').each( function() {
				$(this).children(':eq(1)').after($(this).children(':eq(0)'));
			} );
		}
	} );
</script>
