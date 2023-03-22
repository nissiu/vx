<div class="ts-review-bars">
	
	<div class="ts-percentage-bar excellent">
		<div class="ts-bar-data">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_review_excellent_icon') ) ?: \Voxel\svg( 'happy.svg' ) ?>
			<p><?= _x( 'Excellent', 'reviews', 'voxel' ) ?></p>
			<span><?= absint( $pct['excellent'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['excellent'] ) ?>%;"></div>
		</div>
	</div>
	<div class="ts-percentage-bar very-good">
		<div class="ts-bar-data">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_review_verygood_icon') ) ?: \Voxel\svg( 'happy-2.svg' ) ?>
			<p><?= _x( 'Very good', 'reviews', 'voxel' ) ?></p>
			<span><?= absint( $pct['very_good'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['very_good'] ) ?>%;"></div>
		</div>
	</div>

	<div class="ts-percentage-bar good">
		<div class="ts-bar-data">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_review_good_icon') ) ?: \Voxel\svg( 'smile.svg' ) ?>
			<p><?= _x( 'Good', 'reviews', 'voxel' ) ?></p>
			<span><?= absint( $pct['good'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['good'] ) ?>%;"></div>
		</div>
	</div>

	<div class="ts-percentage-bar fair">
		<div class="ts-bar-data">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_review_fair_icon') ) ?: \Voxel\svg( 'speechless.svg' ) ?>
			<p><?= _x( 'Fair', 'reviews', 'voxel' ) ?></p>
			<span><?= absint( $pct['fair'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['fair'] ) ?>%;"></div>
		</div>
	</div>

	<div class="ts-percentage-bar poor">
		<div class="ts-bar-data">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_review_poor_icon') ) ?: \Voxel\svg( 'sad.svg' ) ?>
			<p><?= _x( 'Poor', 'reviews', 'voxel' ) ?></p>
			<span><?= absint( $pct['poor'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['poor'] ) ?>%;"></div>
		</div>
	</div>
</div>

