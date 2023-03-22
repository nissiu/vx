<?php
$current_post = \Voxel\get_current_post();
if ( ! $current_post ) {
	return;
}
?>
<div class="ts-action-wrap ts-popup-component">
	<a href="#" ref="target" class="ts-action-con" role="button" rel="nofollow">
		<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
		<?= $action['ts_acw_initial_text'] ?>
	</a>
	<popup v-cloak ref="popup">
		<div class="ts-popup-head ts-sticky-top flexify hide-d">
			<div class="ts-popup-name flexify">
				<?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?>
				<p><?= _x( 'Share post', 'share post action', 'voxel' ) ?></p>
			</div>
			<ul class="flexify simplify-ul">
				<li class="flexify ts-popup-close">
					<a role="button" rel="nofollow" @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_close_ico') ) ?: \Voxel\svg( 'close.svg' ) ?>
					</a>
				</li>
			</ul>
		</div>
		<div class="ts-term-dropdown ts-md-group">
			<ul class="simplify-ul ts-term-dropdown-list min-scroll">
				<li>
					<a
						href="#"
						@click.prevent="Voxel.copy( <?= esc_attr( wp_json_encode( $current_post->get_link() ) ) ?> ); $refs.popup.blur();"
						class="flexify" role="button" rel="nofollow"
					>
						<div class="ts-term-icon">
							<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_link_ico') ) ?: \Voxel\svg( 'link-alt.svg' ) ?></span>
						</div>
						<p><?= _x( 'Copy link', 'share post action', 'voxel' ) ?></p>
					</a>
				</li>
				<li>
					<a rel="nofollow" href="#" class="flexify" v-if="navigator.share" @click.prevent="Voxel.share( <?= esc_attr( wp_json_encode( [
						'title' => $current_post->get_title(),
						'url' => $current_post->get_link(),
					] ) ) ?> ); $refs.popup.blur();">
						<div class="ts-term-icon">
							<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_share_ico') ) ?: \Voxel\svg( 'share.svg' ) ?></span>
						</div>
						<p><?= _x( 'Share via...', 'share post action', 'voxel' ) ?></p>
					</a>
				</li>
			</ul>
		</div>
	</popup>
</div>
