<div class="vx-single-customer">
	<div class="vx-card-ui" >

		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				<p>Customer</p>
			</div>
			<div class="vx-card-content">
				<div class="vx-group">
					<?= $user->get_avatar_markup(40) ?>
					<a href="<?= esc_url( $user->get_edit_link() ) ?>"><?= $user->get_display_name() ?></a>
				</div>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				<p>User ID</p>
			</div>
			<div class="vx-card-content">
				<a href="<?= esc_url( $user->get_edit_link() ) ?>"><?= $user->get_id() ?></a>
			</div>
		</div>
		<div class="vx-card no-wp-style">
			<div class="vx-card-head">
				<p>Email</p>
			</div>
			<div class="vx-card-content">
				<?= sprintf( '<a href="mailto:%s">%s</a>', esc_attr( $user->get_email() ), esc_html( $user->get_email() ) ) ?>
			</div>
		</div>

		<div class="vx-card full no-wp-style">
			<div class="vx-card-head">
				<p>Plan details</p>
			</div>
			<div class="vx-card-content">
				<table class="form-table">
					<tbody>

						<!-- <tr>
							<th>Username</th>
							<td><a href="<?= esc_url( $user->get_edit_link() ) ?>"><?= $user->get_display_name() ?></a></td>
						</tr>
						<tr>
							<th>User ID</th>
							<td><?= $user->get_id() ?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><?= sprintf( '<a href="mailto:%s">%s</a>', esc_attr( $user->get_email() ), esc_html( $user->get_email() ) ) ?></td>
						</tr> -->
						<tr>
							<th>Active plan</th>
							<td><?= sprintf( '<strong><a href="%s">%s</a></strong>', $membership->plan->get_edit_link(), $membership->plan->get_label() ) ?></td>
						</tr>

						<?php if ( $membership->get_type() === 'subscription' ): ?>
							<tr>
								<th>Amount</th>
								<td>
									<?= sprintf(
										'<strong>%s</strong> %s',
										\Voxel\currency_format( $membership->get_amount(), $membership->get_currency() ),
										\Voxel\interval_format( $membership->get_interval(), $membership->get_interval_count() )
									) ?>
								</td>
							</tr>
							<tr>
								<th>Status</th>
								<td>
									<?= sprintf(
										'<span class="%s">%s</span>',
										$membership->is_active() ? 'active' : '',
										ucwords( str_replace( '_', ' ', $membership->get_status() ) )
									) ?>
								</td>
							</tr>
							<tr>
								<th>Created</th>
								<td>
									<?php if ( $timestamp = strtotime( $membership->get_created_at() ) ): ?>
										<?= \Voxel\datetime_format( $timestamp ) ?>
									<?php else: ?>
										&mdash;
									<?php endif ?>
								</td>
							</tr>

							<?php if ( $membership->will_cancel_at_period_end() ): ?>
								<tr>
									<th>Ends on</th>
									<td><?= \Voxel\date_format( $membership->get_current_period_end() ) ?> (renewal canceled by user)</td>
								</tr>
							<?php elseif ( $membership->get_status() === 'trialing' ): ?>
								<tr>
									<th>Trial end date</th>
									<td><?= \Voxel\date_format( $membership->get_trial_end() ) ?></td>
								</tr>
							<?php elseif ( $membership->get_status() === 'active' ): ?>
								<tr>
									<th>Renews on</th>
									<td><?= \Voxel\date_format( $membership->get_current_period_end() ) ?></td>
								</tr>
							<?php endif ?>
						<?php elseif ( $membership->get_type() === 'payment' ): ?>
							<tr>
								<th>Amount</th>
								<td>
									<?php if ( floatval( $membership->get_amount() ) === 0.0 ): ?>
										Free
									<?php else: ?>
										<?= sprintf(
											'<strong>%s</strong> one time payment',
											\Voxel\currency_format( $membership->get_amount(), $membership->get_currency() )
										) ?>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>Status</th>
								<td>
									<?= sprintf(
										'<span class="%s">%s</span>',
										$membership->is_active() ? 'active' : '',
										ucwords( str_replace( '_', ' ', $membership->get_status() ) )
									) ?>
								</td>
							</tr>
							<tr>
								<th>Created</th>
								<td>
									<?php if ( $timestamp = strtotime( $membership->get_created_at() ) ): ?>
										<?= \Voxel\datetime_format( $timestamp ) ?>
									<?php else: ?>
										&mdash;
									<?php endif ?>
								</td>
							</tr>
						<?php elseif ( $membership->get_type() === 'default' ): ?>
							<?php if ( $membership->plan->get_key() === 'default' ): ?>
								<tr>
									<th>Status</th>
									<td>This user does not have an active paid membership plan.</td>
								</tr>
							<?php else: ?>
								<tr>
									<th>Status</th>
									<td>This membership plan was manually assigned.</td>
								</tr>
							<?php endif ?>
						<?php endif ?>


						<tr>
							<th>Customer ID</th>
							<td>
								<?php if ( $customer_id = $user->get_stripe_customer_id() ): ?>
									<?= sprintf(
										'<a href="%s" target="_blank">%s %s</a>',
										$stripe_base_url . 'customers/' . $user->get_stripe_customer_id(),
										$user->get_stripe_customer_id(),
										'<i class="las la-external-link-alt"></i>'
									) ?>
								<?php else: ?>
									&mdash;
								<?php endif ?>
							</td>
						</tr>

						<?php if ( $membership->get_type() === 'subscription' ): ?>
							<tr>
								<th>Subscription ID</th>
								<td><?= sprintf(
									'<a href="%s" target="_blank">%s %s</a>',
									$stripe_base_url . 'subscriptions/' . $membership->get_subscription_id(),
									$membership->get_subscription_id(),
									'<i class="las la-external-link-alt"></i>'
								) ?></td>
							</tr>
						<?php elseif ( $membership->get_type() === 'payment' ): ?>
							<tr>
								<th>Payment intent ID</th>
								<td><?= sprintf(
									'<a href="%s" target="_blank">%s %s</a>',
									$stripe_base_url . 'payments/' . $membership->get_payment_intent(),
									$membership->get_payment_intent(),
									'<i class="las la-external-link-alt"></i>'
								) ?></td>
							</tr>
						<?php endif ?>

					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>




