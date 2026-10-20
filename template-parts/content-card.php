<?php
?>
<article <?php post_class( 'group flex flex-col-reverse gap-4 py-8 sm:flex-row sm:items-center sm:gap-8' ); ?>>
	<div class="min-w-0 flex-1">
		<div class="flex items-center gap-2 text-xs">
			<?php
			$inkwell_categories = get_the_category();
			if ( ! empty( $inkwell_categories ) ) :
				?>
				<a href="<?php echo esc_url( get_category_link( $inkwell_categories[0] ) ); ?>" class="font-medium text-brand-600 hover:underline dark:text-brand-400"><?php echo esc_html( $inkwell_categories[0]->name ); ?></a>
				<span aria-hidden="true" class="text-slate-400">&middot;</span>
			<?php endif; ?>
			<time class="text-slate-500 dark:text-slate-400" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</div>
		<h2 class="mt-2 font-serif text-xl font-bold leading-snug sm:text-2xl">
			<a href="<?php the_permalink(); ?>" class="text-slate-900 transition-colors hover:text-brand-700 dark:text-white dark:hover:text-brand-400"><?php the_title(); ?></a>
		</h2>
		<p class="mt-2 leading-relaxed text-slate-600 line-clamp-2 dark:text-slate-400"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<div class="mt-3 flex items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 24, '', '', array( 'class' => 'h-6 w-6 rounded-full' ) ); ?>
			<span><?php the_author(); ?></span>
			<span aria-hidden="true">&middot;</span>
			<span><?php inkwell_reading_time(); ?></span>
		</div>
	</div>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="block shrink-0 sm:w-52 lg:w-64" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'inkwell-card', array( 'class' => 'aspect-[2/1] w-full rounded-lg object-cover transition-opacity group-hover:opacity-90' ) ); ?>
		</a>
	<?php endif; ?>
</article>
