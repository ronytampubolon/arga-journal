<?php
get_header();
?>
<main id="primary" class="mx-auto flex min-h-[50vh] max-w-xl flex-col items-center justify-center px-4 py-20 text-center sm:px-6">
	<h1 class="font-serif text-7xl font-bold tracking-tight text-slate-900 dark:text-white">404</h1>
	<p class="mt-4 text-lg text-slate-600 dark:text-slate-300"><?php esc_html_e( 'The page you are looking for wandered off.', 'inkwell' ); ?></p>
	<div class="mt-8 w-full max-w-sm">
		<?php get_search_form(); ?>
	</div>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-8 rounded-full bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><?php esc_html_e( 'Back to home', 'inkwell' ); ?></a>
</main>
<?php
get_footer();
