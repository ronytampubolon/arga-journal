<?php
?>
<form role="search" method="get" class="search-form relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="inkwell-search-field" class="sr-only"><?php esc_html_e( 'Search', 'inkwell' ); ?></label>
	<input type="search" id="inkwell-search-field" class="search-field w-32 rounded-full border border-slate-300 bg-transparent py-1.5 pl-9 pr-3 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:w-52 focus:border-brand-500 dark:border-slate-700 dark:text-slate-100" placeholder="<?php esc_attr_e( 'Search', 'inkwell' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-brand-600 dark:hover:text-brand-400" aria-label="<?php esc_attr_e( 'Submit search', 'inkwell' ); ?>">
		<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
	</button>
</form>
