<?php
/**
 * Post feed template.
 *
 * @since 1.0
 */
?>

<?php if ( isset( $results['total_count'] ) ): ?>
	<div class="post-feed-header <?= $results['total_count'] === 0 ? 'hidden' : '' ?>">
		<p class="result-count"><?= \Voxel\count_format( count( $results['ids'] ), $results['total_count'] ) ?></p>
	</div>
<?php endif ?>

<div
	class="post-feed-grid <?= $this->get_settings('ts_wrap_feed') ?> <?= $this->get_settings('ts_wrap_feed') === 'ts-feed-nowrap' ? 'min-scroll min-scroll-h' : '' ?>
		<?= $this->get_settings('ts_loading_style') ?> <?= isset( $search_form ) ? 'sf-post-feed' : '' ?> <?= empty( $results['ids'] ) ? 'post-feed-no-results' : '' ?>"
>
	<?= $results['render'] ?? '' ?>
</div>

<?php require locate_template( 'templates/widgets/post-feed/pagination.php' ) ?>
<?php require locate_template( 'templates/widgets/post-feed/no-results.php' ) ?>
<?php require locate_template( 'templates/widgets/post-feed/carousel-nav.php' ) ?>
