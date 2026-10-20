<?php
get_header();
?>
<main id="primary" class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14">
	<header class="border-b border-slate-200 pb-8 dark:border-slate-800">
		<h1 class="font-serif text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
			<?php
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Results for &ldquo;%s&rdquo;', 'Argamora' ),
				'<span class="text-brand-700 dark:text-brand-400">' . esc_html( get_search_query( false ) ) . '</span>'
			);
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="divide-y divide-slate-200 dark:divide-slate-800">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => esc_html__( '&larr; Newer', 'Argamora' ),
				'next_text' => esc_html__( 'Older &rarr;', 'Argamora' ),
			)
		);
		?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>
<?php
get_footer();
