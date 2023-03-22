<?php
$current_post = \Voxel\get_current_post();
$status = is_user_logged_in() && $current_post ? \Voxel\current_user()->get_follow_status( 'post', $current_post->get_id() ) : null;
$is_active = $status === \Voxel\FOLLOW_ACCEPTED;
$is_intermediate = $status === \Voxel\FOLLOW_REQUESTED;
?>
<a
	href="<?= esc_url( add_query_arg( [
		'vx' => 1,
		'action' => 'user.follow_post',
		'post_id' => $current_post ? $current_post->get_id() : null,
		'_wpnonce' => wp_create_nonce( 'vx_user_follow' ),
	], home_url( '/' ) ) ) ?>"
	class="ts-action-con ts-action-follow <?= $is_active ? 'active' : '' ?> <?= $is_intermediate ? 'intermediate' : '' ?>" role="button" rel="nofollow">
	<span class="ts-initial">
		<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
	</span>

	<span class="ts-intermediate">
		<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_intermediate_icon'] ) ?></div><?= $action['ts_acw_intermediate_text'] ?>
	</span>

	<!--Reveal span when action is clicked (active class is added to the li) -->
	<span class="ts-reveal">
		<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_reveal_icon'] ) ?></div><?= $action['ts_acw_reveal_text'] ?>
	</span>
</a>
