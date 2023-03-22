<script type="text/html" id="orders-subscription-status">
	<p v-if="subscription.cancel_at_period_end">
		<?= \Voxel\replace_vars(
			_x( 'Your subscription will be cancelled on @period_end.
				Click <a:reactivate>here</a> to reactivate.', 'single order', 'voxel' ),
			[
				'@period_end' => '{{ subscription.current_period_end }}',
				'<a:reactivate>' => '<a href="#" @click.prevent="order.doAction(\'customer.subscriptions.reactivate\')">',
			]
		) ?>
	</p>
	<p v-else-if="subscription.status === 'trialing'">
		<?= \Voxel\replace_vars( _x( 'Your trial ends on @trial_end', 'single order', 'voxel' ), [
			'@trial_end' => '{{ subscription.trial_end }}',
		] ) ?>
	</p>
	<p v-else-if="subscription.status === 'active'">
		<?= \Voxel\replace_vars( _x( 'Your subscription renews on @period_end', 'single order', 'voxel' ), [
			'@period_end' => '{{ subscription.current_period_end }}',
		] ) ?>
	</p>
	<p v-else-if="subscription.status === 'incomplete'">
		<?= \Voxel\replace_vars(
			_x( '<a:update>Update your payment method</a>,
				then <a:finalize>finalize payment</a>
				to activate your subscription.', 'single order', 'voxel' ),
			[
				'<a:update>' => '<a href="#" @click.prevent="order.doAction(\'customer.portal\')">',
				'<a:finalize>' => '<a href="#" @click.prevent="order.doAction(\'customer.subscriptions.finalize_payment\')">',
			]
		) ?>
	</p>
	<p v-else-if="subscription.status === 'incomplete_expired'">
		<?php _x( 'Subscription payment failed', 'single order', 'voxel' ) ?>
	</p>
	<p v-else-if="subscription.status === 'past_due'">
		<?= \Voxel\replace_vars(
			_x( 'Subscription renewal failed. <a:update>Update payment method</a>,
				then <a:finalize>finalize payment</a>
				to reactivate your subscription.', 'single order', 'voxel' ),
			[
				'<a:update>' => '<a href="#" @click.prevent="order.doAction(\'customer.portal\')">',
				'<a:finalize>' => '<a href="#" @click.prevent="order.doAction(\'customer.subscriptions.finalize_payment\')">',
			]
		) ?>
	</p>
	<p v-else-if="subscription.status === 'canceled'">
		<?php _x( 'Subscription has been canceled', 'single order', 'voxel' ) ?>
	</p>
	<p v-else-if="subscription.status === 'unpaid'">
		<?= \Voxel\replace_vars(
			_x( 'Subscription has been deactivated due to failed renewal payments.
				<a:update>Update payment method</a>, then <a:finalize>finalize payment</a>
				to reactivate your subscription.', 'single order', 'voxel' ),
			[
				'<a:update>' => '<a href="#" @click.prevent="order.doAction(\'customer.portal\')">',
				'<a:finalize>' => '<a href="#" @click.prevent="order.doAction(\'customer.subscriptions.finalize_payment\')">',
			]
		) ?>
	</p>
</script>
