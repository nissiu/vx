<?php

get_header();
\Voxel\print_header(); ?>

<div class="archive-page">
	<h1><?php the_archive_title() ?></h1>
	<p><?php the_archive_description() ?></p>
	<?php if ( have_posts() ): ?>
		<ul>
			<?php while ( have_posts() ): the_post(); ?>
				<li>
					<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
				</li>
			<?php endwhile ?>
		</ul>
		<?php echo paginate_links() ?>
	<?php else: ?>
		<p><?= __( 'No results. Try another search.', 'voxel' ) ?></p>
	<?php endif ?>
</div>

<?php
\Voxel\print_footer();
get_footer();
