<!-- Advanced list widget -->
<ul class="flexify simplify-ul ts-advanced-list ts-al-<?= $this->get_settings_for_display('ts_al_orientation') ?>">
	<?php foreach ($this->get_settings_for_display('ts_actions') as $i => $action): ?>
		<li class="elementor-repeater-item-<?= $action['_id'] ?> flexify ts-action elementor-column <?= $this->get_settings_for_display('ts_al_columns_no') ?>">
			<?php if ($action['ts_action_type'] === 'none'): ?>
				<div class="ts-action-con">
					<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
				</div>
			<?php elseif ($action['ts_action_type'] === 'action_link'): ?>
				<?php $this->add_link_attributes( 'ts_action_link_'.$i, $action['ts_action_link'] ) ?>
				<a <?= $this->get_render_attribute_string( 'ts_action_link_'.$i ) ?> class="ts-action-con">
					<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
					<?= $action['ts_acw_initial_text'] ?>
				</a>
			<?php elseif ($action['ts_action_type'] === 'back_to_top'): ?>
				<a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;" class="ts-action-con">
					<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
					<?= $action['ts_acw_initial_text'] ?>
				</a>
			<?php elseif ($action['ts_action_type'] === 'direct_message'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/direct-message-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'direct_message_user'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/direct-message-user-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'action_save'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/save-post-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'edit_post'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/edit-post-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'delete_post'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/delete-post-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'share_post'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/share-post-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'action_follow'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/follow-user-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'action_follow_post'): ?>
				<?php require locate_template( 'templates/widgets/advanced-list/follow-post-action.php' ) ?>
			<?php elseif ($action['ts_action_type'] === 'select_addition'): ?>
				<a role="button" rel="nofollow" href="#" class="ts-action-con ts-use-addition" data-id="<?= esc_attr( $action['ts_addition_id'] ) ?>">
					<span class="ts-initial">
						<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
					</span>
					<span class="ts-reveal">
						<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_reveal_icon'] ) ?></div><?= $action['ts_acw_reveal_text'] ?>
					</span>
				</a>
			<?php endif ?>
		</li>
	<?php endforeach ?>
</ul>
