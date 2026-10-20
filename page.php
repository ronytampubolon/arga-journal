<?php
get_header();
?>
<main id="primary" class="pb-16">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<header class="mx-auto max-w-3xl px-4 pt-12 text-center sm:px-6 sm:pt-16">
				<h1 class="font-serif text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl"><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="mx-auto mt-10 max-w-5xl px-4 sm:px-6">
					<?php the_post_thumbnail( 'large', array( 'class' => 'aspect-[2/1] w-full rounded-xl object-cover' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="entry-content prose prose-neutral mx-auto mt-10 max-w-prose px-4 font-serif sm:px-6 dark:prose-invert prose-a:text-brand-600 dark:prose-a:text-brand-400 prose-img:rounded-xl">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="page-links mt-8 text-sm">',
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</article>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
