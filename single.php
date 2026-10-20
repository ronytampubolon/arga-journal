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
				<?php
				$inkwell_categories = get_the_category();
				if ( ! empty( $inkwell_categories ) ) :
					?>
					<div class="flex flex-wrap justify-center gap-2">
						<?php foreach ( array_slice( $inkwell_categories, 0, 2 ) as $inkwell_category ) : ?>
							<a href="<?php echo esc_url( get_category_link( $inkwell_category ) ); ?>" class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700 transition-colors hover:bg-brand-100 dark:bg-brand-500/10 dark:text-brand-400 dark:hover:bg-brand-500/20"><?php echo esc_html( $inkwell_category->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<h1 class="mt-4 font-serif text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="mt-4 text-lg leading-relaxed text-slate-500 font-body dark:text-slate-400"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div class="mt-8 flex items-center justify-center gap-3 text-sm text-slate-500 dark:text-slate-400">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'class' => 'h-10 w-10 rounded-full' ) ); ?>
					<div class="text-left">
						<div class="font-semibold text-slate-900 dark:text-white"><?php the_author(); ?></div>
						<div class="flex items-center gap-1.5 text-xs">
							<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							<span aria-hidden="true">&middot;</span>
							<span><?php inkwell_reading_time(); ?></span>
						</div>
					</div>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="mx-auto mt-10 max-w-5xl px-4 sm:px-6">
					<?php the_post_thumbnail( 'large', array( 'class' => 'aspect-[2/1] w-full rounded-xl object-cover' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="entry-content prose prose-neutral mx-auto mt-10 max-w-prose px-4 font-body sm:px-6 dark:prose-invert prose-a:text-brand-600 dark:prose-a:text-brand-400 prose-img:rounded-xl">
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

			<footer class="mx-auto mt-12 max-w-prose px-4 sm:px-6">
				<?php
				$inkwell_tags = get_the_tags();
				if ( $inkwell_tags ) :
					?>
					<div class="flex flex-wrap gap-2">
						<?php foreach ( $inkwell_tags as $inkwell_tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $inkwell_tag ) ); ?>" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">#<?php echo esc_html( $inkwell_tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php
				$inkwell_bio = get_the_author_meta( 'description' );
				if ( $inkwell_bio ) :
					?>
					<aside class="mt-10 flex gap-4 rounded-2xl border border-slate-200 p-6 dark:border-slate-800">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 56, '', '', array( 'class' => 'h-14 w-14 rounded-full' ) ); ?>
						<div class="min-w-0">
							<p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500"><?php esc_html_e( 'Written by', 'inkwell' ); ?></p>
							<h2 class="mt-0.5 font-serif text-lg font-bold text-slate-900 dark:text-white"><?php the_author(); ?></h2>
							<p class="mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400"><?php echo esc_html( $inkwell_bio ); ?></p>
						</div>
					</aside>
				<?php endif; ?>
			</footer>
		</article>

		<nav class="mx-auto mt-12 flex max-w-prose items-start justify-between gap-6 border-t border-slate-200 px-4 pt-8 text-sm sm:px-6 dark:border-slate-800" aria-label="<?php esc_attr_e( 'Post navigation', 'inkwell' ); ?>">
			<div class="group min-w-0">
				<?php previous_post_link( '%link', '<span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">' . esc_html__( 'Previous', 'inkwell' ) . '</span><span class="mt-1 block truncate font-serif text-base font-semibold text-slate-900 group-hover:text-brand-700 dark:text-white dark:group-hover:text-brand-400">%title</span>' ); ?>
			</div>
			<div class="group min-w-0 text-right">
				<?php next_post_link( '%link', '<span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">' . esc_html__( 'Next', 'inkwell' ) . '</span><span class="mt-1 block truncate font-serif text-base font-semibold text-slate-900 group-hover:text-brand-700 dark:text-white dark:group-hover:text-brand-400">%title</span>' ); ?>
			</div>
		</nav>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
