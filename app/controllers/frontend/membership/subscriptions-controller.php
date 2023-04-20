<?php

namespace Voxel\Controllers\Frontend\Membership;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Subscriptions_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel/membership/subscription-updated', '@subscription_updated' );
		$this->on( 'voxel_ajax_plans.retry_payment', '@retry_payment' );
		$this->on( 'voxel_ajax_plans.cancel_plan', '@cancel_plan' );
		$this->on( 'voxel_ajax_plans.reactivate_plan', '@reactivate_plan' );
	}

	protected function subscription_updated( $subscription ) {
		$plan_key = $subscription->metadata['voxel:plan'];
		$plan = \Voxel\Membership\Plan::get( $plan_key );
		if ( ! $plan ) {
			throw new \Exception( sprintf( 'Plan "%s" not found for subscription "%s"', $plan_key, $subscription->id ) );
		}

		$user = \Voxel\User::get_by_customer_id( $subscription->customer );
		if ( ! $user ) {
			throw new \Exception( sprintf( 'Customer ID "%s" does not belong to any registered user (subscription "%s")', $subscription->customer, $subscription->id ) );
		}

		// $subscription->status: trialing, active, incomplete, incomplete_expired, past_due, canceled, unpaid
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_plan' : 'voxel:plan';
		update_user_meta( $user->get_id(), $meta_key, wp_slash( wp_json_encode( [
			'plan' => $plan->get_key(),
			'type' => 'subscription',
			'subscription_id' => $subscription->id,
			'price_id' => $subscription->plan->id,
			'status' => $subscription->status,
			'trial_end' => $subscription->trial_end,
			'current_period_end' => $subscription->current_period_end,
			'cancel_at_period_end' => $subscription->cancel_at_period_end,
			'amount' => $subscription->plan->amount,
			'currency' => $subscription->plan->currency,
			'interval' => $subscription->plan->interval,
			'interval_count' => $subscription->plan->interval_count,
			'created' => date( 'Y-m-d H:i:s', $subscription->created ),
		] ) ) );
		do_action( 'voxel/membership/pricing-plan-updated', $user, $user->get_membership(), $user->get_membership( $refresh_cache = true ) );
	}

	protected function retry_payment() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_retry_payment' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			if ( $membership->get_type() !== 'subscription' || ! in_array( $membership->get_status(), [ 'incomplete', 'past_due', 'unpaid' ], true ) ) {
				throw new \Exception( __( 'Invalid request', 'voxel' ) );
			}

			$subscription = \Stripe\Subscription::retrieve( $membership->get_subscription_id() );

			if ( $membership->get_status() === 'unpaid' ) {
				$stripe->invoices->finalizeInvoice( $subscription->latest_invoice, [
					'auto_advance' => true,
				] );
			}

			$stripe->invoices->pay( $subscription->latest_invoice );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Invoice was paid successfully.', 'pricing plans', 'voxel' ),
				'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function cancel_plan() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_cancel_plan' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			if ( ! ( $membership->get_type() === 'subscription' && $membership->is_active() ) ) {
				throw new \Exception( __( 'Invalid request', 'voxel' ) );
			}

			if ( \Voxel\get( 'settings.membership.cancel.behavior', 'at_period_end' ) === 'immediately' ) {
				$subscription = $stripe->subscriptions->cancel( $membership->get_subscription_id() );
				do_action( 'voxel/membership/subscription-updated', $subscription );
			} else {
				$subscription = \Stripe\Subscription::update( $membership->get_subscription_id(), [
					'cancel_at_period_end' => true,
				] );
				do_action( 'voxel/membership/subscription-updated', $subscription );
			}

			return wp_send_json( [
				'success' => true,
				'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function reactivate_plan() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_reactivate_plan' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			if ( $membership->get_type() !== 'subscription' || ! $membership->will_cancel_at_period_end() ) {
				throw new \Exception( __( 'Invalid request', 'voxel' ) );
			}

			$subscription = \Stripe\Subscription::update( $membership->get_subscription_id(), [
				'cancel_at_period_end' => false,
			] );

			do_action( 'voxel/membership/subscription-updated', $subscription );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Subscription has been reactivated.', 'pricing plans', 'voxel' ),
				'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}
}
