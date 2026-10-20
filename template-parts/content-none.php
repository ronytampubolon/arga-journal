<?php
?>
<section class="py-16 text-center">
	<h2 class="font-serif text-2xl font-bold text-slate-900 dark:text-white"><?php esc_html_e( 'Nothing found', 'Argamora' ); ?></h2>
	<p class="mt-2 text-slate-500 dark:text-slate-400"><?php esc_html_e( 'It looks like there is nothing here yet. Try a search instead.', 'Argamora' ); ?></p>
	<div class="mx-auto mt-6 max-w-sm">
		<?php get_search_form(); ?>
	</div>
</section>
