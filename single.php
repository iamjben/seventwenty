<?php get_header(); ?>

<div class="page">
	<main class="page__content">
		
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	
	</main>
</div>

<?php
get_sidebar();
get_footer();
