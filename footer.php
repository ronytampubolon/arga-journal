<?php
?>
<footer class="border-t border-slate-200 dark:border-slate-800">
	<div class="mx-auto flex max-w-5xl flex-col items-center gap-4 px-4 py-10 text-sm text-slate-500 dark:text-slate-400 sm:px-6">
		<?php
		if ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'flex flex-wrap justify-center gap-x-6 gap-y-2',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
		}
		?>
		<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<p class="text-xs text-slate-400 dark:text-slate-500"><?php esc_html_e( 'Built with ❤️', 'Argamora' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
