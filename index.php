<?php
get_header();
?>
<main id="primary" class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14">
	<?php if ( is_home() && ! is_paged() ) : ?>
		<header class="border-b border-slate-200 pb-12 pt-4 text-center dark:border-slate-800 sm:pt-8">
			<?php $inkwell_kicker = get_theme_mod( 'inkwell_hero_kicker', __( 'Personal blog', 'inkwell' ) ); ?>
			<?php if ( $inkwell_kicker ) : ?>
				<p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 dark:text-brand-400"><?php echo esc_html( $inkwell_kicker ); ?></p>
			<?php endif; ?>
			<h1 class="mt-3 font-serif text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl"><?php bloginfo( 'name' ); ?></h1>
			<?php $inkwell_description = get_bloginfo( 'description', 'display' ); ?>
			<?php if ( $inkwell_description ) : ?>
				<p class="mx-auto mt-3 max-w-xl text-lg leading-relaxed text-slate-500 dark:text-slate-400"><?php echo esc_html( $inkwell_description ); ?></p>
			<?php endif; ?>
		</header>
	<?php endif; ?>

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
				'prev_text' => esc_html__( '&larr; Newer', 'inkwell' ),
				'next_text' => esc_html__( 'Older &rarr;', 'inkwell' ),
			)
		);
		?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>
<?php
get_footer();
