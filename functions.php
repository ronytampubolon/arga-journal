<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'Argamora_VERSION', '1.0.0' );

// Do not advertise the WordPress version in the page source.
remove_action( 'wp_head', 'wp_generator' );

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

function Argamora_setup() {
	load_theme_textdomain( 'Argamora', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'Argamora' ),
			'footer'  => __( 'Footer Menu', 'Argamora' ),
		)
	);

	set_post_thumbnail_size( 1200, 675, true );
	add_image_size( 'Argamora-card', 800, 450, true );
}
add_action( 'after_setup_theme', 'Argamora_setup' );

function Argamora_enqueue_assets() {
	wp_enqueue_style( 'Argamora-style', get_stylesheet_uri(), array(), Argamora_VERSION );
	wp_enqueue_style( 'Argamora-app', get_template_directory_uri() . '/assets/css/app.css', array( 'Argamora-style' ), Argamora_VERSION );
	wp_enqueue_script( 'Argamora-app', get_template_directory_uri() . '/assets/js/app.js', array(), Argamora_VERSION, array( 'strategy' => 'defer' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'Argamora_enqueue_assets' );

/**
 * Applies the stored colour scheme before first paint to avoid a flash of the
 * wrong theme. Printed through wp_head() so the tag goes through
 * wp_print_inline_script_tag() and security plugins can attach a CSP nonce.
 */
function Argamora_theme_boot_script() {
	$script = <<<'JS'
(function () {
	try {
		var stored = localStorage.getItem( 'arga-mora' );
		if ( 'dark' === stored || ( ! stored && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ) ) {
			document.documentElement.classList.add( 'dark' );
		}
	} catch ( e ) {}
})();
JS;

	wp_print_inline_script_tag( $script );
}
add_action( 'wp_head', 'Argamora_theme_boot_script', 1 );

function Argamora_fallback_menu() {
	echo '<ul class="flex flex-col gap-1 px-4 py-3 text-sm font-medium sm:px-6 md:flex-row md:items-center md:gap-6 md:bg-transparent md:p-0">';
	echo '<li><a class="text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'Argamora' ) . '</a></li>';
	echo '</ul>';
}

function Argamora_menu_link_classes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'footer' ), true ) ) {
		$atts['class'] = 'text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'Argamora_menu_link_classes', 10, 3 );

function Argamora_reading_time() {
	$words   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	printf( esc_html__( '%d min read', 'Argamora' ), $minutes );
}

function Argamora_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'Argamora_excerpt_length' );

function Argamora_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'Argamora_excerpt_more' );

function Argamora_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<article class="flex gap-3">
			<div class="shrink-0">
				<?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'h-10 w-10 rounded-full' ) ); ?>
			</div>
			<div class="min-w-0 flex-1">
				<footer class="flex flex-wrap items-baseline gap-x-2 text-sm">
					<span class="font-semibold text-slate-900 dark:text-white"><?php comment_author_link(); ?></span>
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>" class="text-xs text-slate-500 hover:text-brand-600 dark:text-slate-400">
						<time datetime="<?php comment_time( 'c' ); ?>"><?php printf( esc_html__( '%s ago', 'Argamora' ), esc_html( human_time_diff( get_comment_time( 'U' ) ) ) ); ?></time>
					</a>
				</footer>
				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="mt-1 text-sm text-amber-600 dark:text-amber-400"><?php esc_html_e( 'Your comment is awaiting moderation.', 'Argamora' ); ?></p>
				<?php endif; ?>
				<div class="comment-content mt-1 text-[15px] leading-7 text-slate-700 dark:text-slate-300">
					<?php comment_text(); ?>
				</div>
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => __( 'Reply', 'Argamora' ),
							'before'     => '<span class="mt-1 inline-block">',
							'after'      => '</span>',
						)
					)
				);
				?>
			</div>
		</article>
	<?php
}
