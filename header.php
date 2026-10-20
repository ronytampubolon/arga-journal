<?php
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#ffffff" id="meta-theme-color">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-white font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200' ); ?>>
<?php wp_body_open(); ?>
<a href="#primary" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white"><?php esc_html_e( 'Skip to content', 'Argamora' ); ?></a>

<header class="sticky top-0 z-40 border-b border-slate-900/10 bg-white/80 backdrop-blur dark:border-slate-50/10 dark:bg-slate-950/80">
	<div class="relative mx-auto flex h-16 max-w-5xl items-center justify-between gap-4 px-4 sm:px-6">
		<div class="flex min-w-0 items-center gap-1">
			<button id="nav-toggle" type="button" class="rounded-full p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200 md:hidden" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Toggle navigation', 'Argamora' ); ?>">
				<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
			</button>
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="truncate font-serif text-xl font-bold tracking-tight text-slate-900 transition-colors hover:text-brand-700 dark:text-white dark:hover:text-brand-400"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
			<?php endif; ?>
		</div>

		<div class="flex items-center gap-1 sm:gap-2">
			<div id="primary-menu" class="absolute inset-x-0 top-full hidden border-b border-slate-900/10 bg-white dark:border-slate-50/10 dark:bg-slate-950 md:static md:block md:border-0 md:bg-transparent">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'flex flex-col gap-1 px-4 py-3 text-sm font-medium sm:px-6 md:flex-row md:items-center md:gap-6 md:bg-transparent md:p-0',
						'fallback_cb'    => 'Argamora_fallback_menu',
						'depth'          => 2,
					)
				);
				?>
			</div>
			<div class="hidden sm:block">
				<?php get_search_form(); ?>
			</div>
			<button id="theme-toggle" type="button" class="rounded-full p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'Argamora' ); ?>" aria-pressed="false">
				<svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
				<svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
			</button>
		</div>
	</div>
</header>
