<?php
$customer = $order->get_customer();
$vendor = $order->get_vendor();
$post = $order->get_post();
$product_type = $order->get_product_type();

$pricing = $order->get_pricing_details();
$additions = $order->get_additions_details();
$custom_additions = $order->get_custom_additions_details();
$fields = $order->get_information_fields_details();
$booking = $order->get_booking_details();
$active_tag = $order->get_tag();
$tags = $product_type ? $product_type->get_tags() : [];
$stripe_base_url = \Voxel\Stripe::is_test_mode() ? 'https://dashboard.stripe.com/test/' : 'https://dashboard.stripe.com/';
?>

<div class="vx-single-order">
	<div class="vx-card-ui vx-order-details">
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				Status
			</div>
			<div class="vx-card-content large-text">
				<?= sprintf( '<div class="status-%s">%s</div>', esc_attr( $order->get_status() ), $order->get_status_label() ) ?>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				Amount
			</div>
			<div class="vx-card-content large-text">
				<?= $order->get_price()['amount'] ? sprintf( '<span class="price-amount">%s</span> %s',
					$order->get_price_for_display(),
					$order->get_price_period_for_display()
				) : '&mdash;' ?>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				Customer
			</div>
			<div class="vx-card-content">
				<div class="vx-group">
					<?= $customer ? sprintf( '%s<span class="item-title"><a href="%s">%s</a></span>',
					$customer->get_avatar_markup(32),
					$customer->get_edit_link(),
					$customer->get_display_name()
				) : '&mdash;' ?>
				</div>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				Vendor
			</div>
			<div class="vx-card-content">
				<div class="vx-group">
					<?= $vendor ? sprintf( '%s<span class="item-title"><a href="%s">%s</a></span>',
					$vendor->get_avatar_markup(32),
					$vendor->get_edit_link(),
					$vendor->get_display_name()
				) : '&mdash;' ?>
				</div>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				Post
			</div>
			<div class="vx-card-content">
				<div class="vx-group">
					<?= $post ? sprintf( '%s<span class="item-title"><a href="%s">%s</a></span>',
					$post->get_logo_markup(),
					get_edit_post_link( $post->get_id() ),
					$post->get_title()
				) : '&mdash' ?>
				</div>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				Order ID
			</div>
			<div class="vx-card-content large-text">
				#<?= $order->get_id() ?>
			</div>
		</div>

		<div class="vx-card no-wp-style full">
			<div class="vx-card-head">
				Order details
			</div>
			<div class="vx-card-content">
				<table class="form-table">
					<tbody>

						<tr>
							<th>Product type</th>
							<td><?= $product_type ? sprintf( '<a href="%s">%s</a>',
								$product_type->get_edit_link(),
								$product_type->get_label()
							) : '&mdash;' ?></td>
						</tr>
						<!-- <tr>
							<th>Amount</th>
							<td><?= $order->get_price()['amount'] ? sprintf( '<span class="price-amount">%s</span> %s',
								$order->get_price_for_display(),
								$order->get_price_period_for_display()
							) : '&mdash;' ?></td>
						</tr> -->
						<tr>
							<th>Catalog mode</th>
							<td><?= $order->is_catalog_mode() ? 'Yes' : 'No' ?></td>
						</tr>
						<tr>
							<th>Created</th>
							<td><?= ( $timestamp = strtotime( $order->get_created_at() ) ) ? \Voxel\datetime_format( $timestamp ) : '' ?></td>
						</tr>
						<tr>
							<th>Active tag</th>
							<td><?= $active_tag ? $active_tag->get_label() : 'No active tag' ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="vx-card no-wp-style full">
			<div class="vx-card-head">
				Price breakdown
			</div>
			<div class="vx-card-content">
				<table class="form-table">
					<tbody>
						<tr>
							<th>Base price</th>
							<td><?= $pricing['base_price'] ?></td>
						</tr>
						<?php foreach ( $pricing['additions'] as $addition ): ?>
							<tr>
								<th><?= esc_html( $addition['label'] ) ?></th>
								<td><?= esc_html( $addition['price'] ) ?></td>
							</tr>
						<?php endforeach ?>
						<tr>
							<th>Total</th>
							<td><?= esc_html( $pricing['total'] ) ?> <?= esc_html( $pricing['period'] ) ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<?php if ( ! empty( $booking ) ): ?>
			<div class="vx-card no-wp-style full">
				<div class="vx-card-head">
					Booking
				</div>
				<div class="vx-card-content">
					<table class="form-table">
					<tbody>
						<?php if ( $booking['type'] === 'date_range' ): ?>
							<tr>
								<th>Check-in</th>
								<td><?= $booking['from'] ?></td>
							</tr>
							<tr>
								<th>Check-out</th>
								<td><?= $booking['to'] ?></td>
							</tr>
						<?php elseif ( $booking['type'] === 'timeslot' ): ?>
							<tr>
								<th>Date</th>
								<td><?= $booking['date'] ?></td>
							</tr>
							<tr>
								<th>Timeslot</th>
								<td><?= $booking['from'] ?> to <?= $booking['to'] ?></td>
							</tr>
						<?php else: ?>
							<tr>
								<th>Date</th>
								<td><?= $booking['date'] ?></td>
							</tr>
						<?php endif ?>
					</tbody>
				</table>
				</div>
			</div>
		<?php endif ?>

		<?php if ( ! empty( $additions ) || ! empty( $custom_additions ) ): ?>
			<div class="vx-card no-wp-style full">
				<div class="vx-card-head">
					Additions
				</div>
				<div class="vx-card-content">
					<table class="form-table">
						<tbody>
							<?php foreach ( $additions as $addition ): ?>
								<tr>
									<th><?= esc_html( $addition['label'] ) ?></th>
									<td><?= esc_html( $addition['content'] ) ?></td>
								</tr>
							<?php endforeach ?>
							<?php foreach ( $custom_additions as $addition ): ?>
								<tr>
									<th><?= esc_html( $addition['label'] ) ?></th>
									<td><?= esc_html( $addition['content'] ) ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif ?>

		<?php if ( ! empty( $fields ) ): ?>
			<div class="vx-card no-wp-style full">
				<div class="vx-card-head">
					Information fields
				</div>
				<div class="vx-card-content">
					<table class="form-table">
						<tbody>
							<?php foreach ( $fields as $field ): ?>
								<tr>
									<th><?= $field['label'] ?></th>
									<td>
										<?php if ( $field['type'] === 'file' ): ?>
											<?php foreach ( $field['content'] as $file): ?>
												<p><a href="<?= esc_url( $file['link'] ) ?>" target="_blank"><?= esc_html( $file['name'] ) ?></a></p>
											<?php endforeach ?>
										<?php else: ?>
											<?= esc_html( $field['content'] ) ?>
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif ?>

		<?php if ( $customer ): ?>
			<div class="vx-card no-wp-style full">
				<div class="vx-card-head">
					Customer
				</div>
				<div class="vx-card-content">
					<table class="form-table">
						<tbody>
							<tr>
								<th>Username</th>
								<td><a href="<?= esc_url( $customer->get_edit_link() ) ?>"><?= $customer->get_display_name() ?></a></td>
							</tr>
							<tr>
								<th>User ID</th>
								<td><?= $customer->get_id() ?></td>
							</tr>
							<tr>
								<th>Email</th>
								<td><?= sprintf( '<a href="mailto:%s">%s</a>', esc_attr( $customer->get_email() ), esc_html( $customer->get_email() ) ) ?></td>
							</tr>
							<tr>
								<th>Stripe customer ID</th>
								<td>
									<?php if ( $customer_id = $customer->get_stripe_customer_id() ): ?>
										<?= sprintf(
											'<a href="%s" target="_blank">%s %s</a>',
											$stripe_base_url . 'customers/' . $customer->get_stripe_customer_id(),
											$customer->get_stripe_customer_id(),
											'<i class="las la-external-link-alt"></i>'
										) ?>
									<?php else: ?>
										&mdash;
									<?php endif ?>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>
		<?php endif ?>

		<?php if ( $vendor ): ?>
			<div class="vx-card no-wp-style full">
				<div class="vx-card-head">
					Vendor
				</div>
				<div class="vx-card-content">

					<table class="form-table">
						<tbody>
							<tr>
								<th>Username</th>
								<td><a href="<?= esc_url( $vendor->get_edit_link() ) ?>"><?= $vendor->get_display_name() ?></a></td>
							</tr>
							<tr>
								<th>User ID</th>
								<td><?= $vendor->get_id() ?></td>
							</tr>
							<tr>
								<th>Email</th>
								<td><?= sprintf( '<a href="mailto:%s">%s</a>', esc_attr( $vendor->get_email() ), esc_html( $vendor->get_email() ) ) ?></td>
							</tr>
							<?php if ( $account_id = $vendor->get_stripe_account_id() ): ?>
								<tr>
									<th>Stripe Connect vendor ID</th>
									<td>
										<?= sprintf(
											'<a href="%s" target="_blank">%s %s</a>',
											$stripe_base_url . 'connect/accounts/' . $account_id,
											$vendor->get_stripe_account_id(),
											'<i class="las la-external-link-alt"></i>'
										) ?>
									</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif ?>

		<?php if ( ! $order->is_catalog_mode() ): ?>
			<div class="vx-card no-wp-style full">
				<div class="vx-card-head">
					Stripe
				</div>
				<div class="vx-card-content">
					<table class="form-table">
						<tbody>
							<?php if ( $order->get_mode() === 'subscription' ): ?>
								<tr>
									<th>Subscription ID</th>
									<td><?= sprintf(
										'<a href="%s" target="_blank">%s %s</a>',
										$stripe_base_url . 'subscriptions/' . $order->get_object_id(),
										$order->get_object_id(),
										'<i class="las la-external-link-alt"></i>'
									) ?></td>
								</tr>
							<?php elseif ( $order->get_mode() === 'payment' ): ?>
								<tr>
									<th>Payment intent ID</th>
									<td><?= sprintf(
										'<a href="%s" target="_blank">%s %s</a>',
										$stripe_base_url . 'payments/' . $order->get_object_id(),
										$order->get_object_id(),
										'<i class="las la-external-link-alt"></i>'
									) ?></td>
								</tr>
							<?php endif ?>
							<!-- <tr>
								<td>
									<a href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.sync_with_stripe_backend&order_id='.$order->get_id() ) ) ?>" class="button">Sync order with Stripe</a>
								</td>
							</tr> -->
						</tbody>
					</table>
				</div>
			</div>
		<?php endif ?>
	</div>
	<div class="vx-card-ui">
		<?php if ( in_array( get_current_user_id(), [ $order->get_vendor_id(), $order->get_customer_id() ], true ) ): ?>
			<div>
				<a href="<?= esc_url( $order->get_link() ) ?>" class="ts-button btn-shadow">Manage in frontend</a>
			</div>
		<?php endif ?>
		<?php if ( $vendor && in_array( $order->get_status(), [ 'pending_approval', 'refund_requested' ], true ) ): ?>
			<div class="vx-card ">
				<div class="vx-card-head">
					Vendor actions
				</div>
				<div class="vx-card-content vx-card-btns">
					<?php if ( $order->get_mode() === 'payment' || $order->is_catalog_mode() ): ?>
						<?php if ( $order->get_status() === 'pending_approval' ): ?>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.approve&order_id='.$order->get_id() ) ) ?>" class="button"><?php \Voxel\svg( 'checkmark-circle.svg' ) ?>Approve order</a>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.decline&order_id='.$order->get_id() ) ) ?>" class="button"><?php \Voxel\svg( 'cross-circle.svg' ) ?>Decline order</a>
						<?php elseif ( $order->get_status() === 'refund_requested' ): ?>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.approve_refund&order_id='.$order->get_id() ) ) ?>" class="button"><?php \Voxel\svg( 'checkmark-circle.svg' ) ?>Approve refund</a>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.decline_refund&order_id='.$order->get_id() ) ) ?>" class="button">Decline refund</a>
						<?php endif ?>
					<?php endif ?>
				</div>
			</div>
		<?php endif ?>
		<?php if ( $customer && in_array( $order->get_status(), [ 'pending_approval', 'completed', 'refund_requested' ], true ) ): ?>
			<div class="vx-card ">
				<div class="vx-card-head">
					Customer actions
				</div>
				<div class="vx-card-content vx-card-btns">
					<?php if ( $order->get_mode() === 'payment' || $order->is_catalog_mode() ): ?>
						<?php if ( $order->get_status() === 'pending_approval' ): ?>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.cancel&order_id='.$order->get_id() ) ) ?>" class="button"><?php \Voxel\svg( 'cross-circle.svg' ) ?>Cancel order</a>
						<?php elseif ( $order->get_status() === 'completed' && ! ( $order->is_catalog_mode() && ! $product_type->catalog_refunds_allowed() ) ): ?>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.request_refund&order_id='.$order->get_id() ) ) ?>" class="button">Request a refund</a>
						<?php elseif ( $order->get_status() === 'refund_requested' ): ?>
							<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.cancel_refund_request&order_id='.$order->get_id() ) ) ?>" class="button">Cancel refund request</a>
						<?php endif ?>
					<?php endif ?>
				</div>
			</div>
		<?php endif ?>


		<?php if ( ! empty( $tags ) && $order->get_status() === 'completed' ): ?>

			<div class="vx-card no-wp-style">
				<div class="vx-card-head">
					Apply tag
				</div>
				<div class="vx-card-content vx-card-btns">
					<?php foreach ( $tags as $tag ): ?>
						<a class="vx-card-btn ts-button <?= ( $active_tag && $tag->get_key() === $active_tag->get_key() ) ? 'vx-disabled' : '' ?>" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.apply_tag&order_id='.$order->get_id().'&tag='.$tag->get_key() ) ) ?>"><?= $tag->get_label() ?></a>
					<?php endforeach ?>
				</div>
			</div>
		<?php endif ?>

		<?php if ( ! $order->is_catalog_mode() ): ?>
			<div class="vx-card ">
				<div class="vx-card-head">
					Other
				</div>
				<div class="vx-card-content vx-card-btns">
					<a class="vx-card-btn ts-button" href="<?= esc_url( home_url( '/?vx=1&action=orders.admin.sync_with_stripe_backend&order_id='.$order->get_id() ) ) ?>" class="button"><?php \Voxel\svg( 'reload.svg' ) ?>Sync with Stripe</a>
				</div>
			</div>
		<?php endif ?>
	</div>
</div>
