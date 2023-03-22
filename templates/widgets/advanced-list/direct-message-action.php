<?php
$current_post = \Voxel\get_current_post();
if ( ! ( $current_post && $current_post->can_send_messages() ) ) {
	return;
}
?>
<a href="<?= esc_url( add_query_arg( 'chat', 'p'.$current_post->get_id(), get_permalink( \Voxel\get('templates.inbox') ) ?: home_url('/') ) ) ?>" class="ts-action-con" role="button" rel="nofollow" onclick="Voxel.requireAuth(event)">
	<span class="ts-initial">
		<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
	</span>
</a>
