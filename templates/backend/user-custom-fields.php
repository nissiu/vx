<h3>Membership</h3>
<table class="form-table vx-edit-profile">
	<tr>
		<th><label for="address">Membership plan</label></th>
		<td>
			<select name="vx_details[plan]">
				<?php foreach ( \Voxel\Membership\Plan::active() as $plan ): ?>
					<option value="<?= esc_attr( $plan->get_key() ) ?>" <?= $membership->plan->get_key() === $plan->get_key() ? 'selected' : '' ?>>
						<?= $plan->get_label() ?>
					</option>
				<?php endforeach ?>

				<?php if ( $archived = \Voxel\Membership\Plan::archived() ): ?>
					<optgroup label="Archived plans">
						<?php foreach ( $archived as $plan ): ?>
							<option value="<?= esc_attr( $plan->get_key() ) ?>" <?= $membership->plan->get_key() === $plan->get_key() ? 'selected' : '' ?>>
								<?= $plan->get_label() ?>
							</option>
						<?php endforeach ?>
					</optgroup>
				<?php endif ?>
			</select>
		</td>
	</tr>
</table>
