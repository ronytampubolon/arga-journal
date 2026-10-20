<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INKWELL_VERSION', '1.0.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

function inkwell_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'inkwell' ),
			'footer'  => __( 'Footer Menu', 'inkwell' ),
		)
	);

	set_post_thumbnail_size( 1200, 675, true );
	add_image_size( 'inkwell-card', 800, 450, true );
}
add_action( 'after_setup_theme', 'inkwell_setup' );

function inkwell_enqueue_assets() {
	wp_enqueue_style( 'inkwell-style', get_stylesheet_uri(), array(), INKWELL_VERSION );
	wp_enqueue_style( 'inkwell-app', get_template_directory_uri() . '/assets/css/app.css', array( 'inkwell-style' ), INKWELL_VERSION );
	wp_enqueue_script( 'inkwell-app', get_template_directory_uri() . '/assets/js/app.js', array(), INKWELL_VERSION, array( 'strategy' => 'defer' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'inkwell_enqueue_assets' );

function inkwell_dequeue_classic_theme_styles() {
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'inkwell_dequeue_classic_theme_styles', 20 );

function inkwell_fallback_menu() {
	echo '<ul class="flex flex-col gap-1 px-4 py-3 text-sm font-medium sm:px-6 md:flex-row md:items-center md:gap-6 md:bg-transparent md:p-0">';
	echo '<li><a class="text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'inkwell' ) . '</a></li>';
	echo '</ul>';
}

function inkwell_menu_link_classes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'footer' ), true ) ) {
		$atts['class'] = 'text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'inkwell_menu_link_classes', 10, 3 );

function inkwell_reading_time() {
	$words   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	printf( esc_html__( '%d min read', 'inkwell' ), $minutes );
}

function inkwell_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'inkwell_excerpt_length' );

function inkwell_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'inkwell_excerpt_more' );

function inkwell_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<article class="flex gap-3">
			<div class="shrink-0">
				<?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'h-10 w-10 rounded-full' ) ); ?>
			</div>
			<div class="min-w-0 flex-1">
				<footer class="flex flex-wrap items-baseline gap-x-2 text-sm">
					<span class="font-semibold text-slate-900 dark:text-white"><?php comment_author_link(); ?></span>
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>" class="text-xs text-slate-500 hover:text-brand-600 dark:text-slate-400">
						<time datetime="<?php comment_time( 'c' ); ?>"><?php printf( esc_html__( '%s ago', 'inkwell' ), esc_html( human_time_diff( get_comment_time( 'U' ) ) ) ); ?></time>
					</a>
				</footer>
				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="mt-1 text-sm text-amber-600 dark:text-amber-400"><?php esc_html_e( 'Your comment is awaiting moderation.', 'inkwell' ); ?></p>
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
							'reply_text' => __( 'Reply', 'inkwell' ),
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

/* ---------- SEO & structured data ---------- */

function inkwell_get_description() {
	$description = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$description = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
		}
	} elseif ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'description', get_queried_object_id() );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$description = sprintf( __( 'Search results for "%s".', 'inkwell' ), get_search_query() );
	}

	$description = wp_strip_all_tags( $description );
	$description = trim( preg_replace( '/\s+/', ' ', $description ) );

	return mb_substr( $description, 0, 155 );
}

function inkwell_seo_meta() {
	$description = inkwell_get_description();
	$title       = wp_get_document_title();
	$url         = is_singular() ? get_permalink() : home_url( wp_unslash( $_SERVER['REQUEST_URI'] ) );

	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'large' );
	}
	if ( ! $image ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$image = wp_get_attachment_image_url( $logo_id, 'full' );
		}
	}
	if ( ! $image ) {
		$image = get_site_icon_url();
	}

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	echo '<meta name="twitter:card" content="' . ( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'inkwell_seo_meta', 1 );

function inkwell_owner_id() {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post && ! empty( $post->post_author ) ) {
			return (int) $post->post_author;
		}
	}
	$users = get_users(
		array(
			'role__in' => array( 'administrator' ),
			'number'   => 1,
			'orderby'  => 'ID',
			'order'    => 'ASC',
		)
	);

	return ! empty( $users ) ? (int) $users[0]->ID : 0;
}

function inkwell_jsonld() {
	$graph   = array();
	$owner   = inkwell_owner_id();
	$site_id = home_url( '/' );

	$graph[] = array(
		'@type'       => 'WebSite',
		'@id'         => $site_id . '#website',
		'url'         => $site_id,
		'name'        => get_bloginfo( 'name' ),
		'description' => get_bloginfo( 'description' ),
		'inLanguage'  => get_locale(),
	);

	if ( $owner ) {
		$graph[] = array(
			'@type' => 'Person',
			'@id'   => $site_id . '#person',
			'name'  => get_the_author_meta( 'display_name', $owner ),
			'url'   => $site_id,
		);
	}

	if ( is_singular( 'post' ) ) {
		$post = get_queried_object();

		$article = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink( $post ) . '#article',
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => get_permalink( $post ),
			),
			'headline'         => get_the_title( $post ),
			'description'      => inkwell_get_description(),
			'inLanguage'       => get_locale(),
			'datePublished'    => get_the_date( DATE_W3C, $post ),
			'dateModified'     => get_the_modified_date( DATE_W3C, $post ),
			'author'           => array( '@id' => $site_id . '#person' ),
			'publisher'        => array( '@id' => $site_id . '#person' ),
		);

		if ( has_post_thumbnail( $post ) ) {
			$article['image'] = get_the_post_thumbnail_url( $post, 'large' );
		}

		$categories = get_the_category( $post );
		if ( $categories ) {
			$article['articleSection'] = wp_list_pluck( $categories, 'name' );
		}

		$graph[] = $article;

		$crumbs   = array(
			array(
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => __( 'Home', 'inkwell' ),
				'item'     => $site_id,
			),
		);
		$position = 2;
		if ( $categories ) {
			$crumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $categories[0]->name,
				'item'     => get_category_link( $categories[0] ),
			);
		}
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => get_the_title( $post ),
			'item'     => get_permalink( $post ),
		);

		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => get_permalink( $post ) . '#breadcrumb',
			'itemListElement' => $crumbs,
		);
	} elseif ( is_author() && $author = get_queried_object() ) {
		$graph[] = array(
			'@type'      => 'ProfilePage',
			'@id'        => get_author_posts_url( $author->ID ) . '#profile',
			'url'        => get_author_posts_url( $author->ID ),
			'name'       => $author->display_name,
			'mainEntity' => array(
				'@type'       => 'Person',
				'@id'         => get_author_posts_url( $author->ID ) . '#person',
				'name'        => $author->display_name,
				'description' => get_the_author_meta( 'description', $author->ID ),
			),
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ) ) . '</script>' . "\n";
}
add_action( 'wp_head', 'inkwell_jsonld', 2 );

function inkwell_robots( $robots ) {
	if ( is_attachment() || is_date() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'inkwell_robots' );

/* ---------- Customizer: hero text + Google Fonts ---------- */

function inkwell_google_fonts() {
	return array(
		'system-serif'     => array( 'label' => __( 'System serif (default)', 'inkwell' ), 'family' => '', 'italics' => false, 'stack' => 'charter, Cambria, Georgia, "Times New Roman", serif' ),
		'system-sans'      => array( 'label' => __( 'System sans', 'inkwell' ), 'family' => '', 'italics' => false, 'stack' => 'ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif' ),
		'newsreader'       => array( 'label' => 'Newsreader', 'family' => 'Newsreader', 'italics' => true, 'stack' => '"Newsreader", Georgia, serif' ),
		'source-serif-4'   => array( 'label' => 'Source Serif 4', 'family' => 'Source Serif 4', 'italics' => true, 'stack' => '"Source Serif 4", Georgia, serif' ),
		'lora'             => array( 'label' => 'Lora', 'family' => 'Lora', 'italics' => true, 'stack' => '"Lora", Georgia, serif' ),
		'playfair-display' => array( 'label' => 'Playfair Display', 'family' => 'Playfair Display', 'italics' => true, 'stack' => '"Playfair Display", Georgia, serif' ),
		'libre-baskerville' => array( 'label' => 'Libre Baskerville', 'family' => 'Libre Baskerville', 'italics' => true, 'stack' => '"Libre Baskerville", Georgia, serif' ),
		'spectral'         => array( 'label' => 'Spectral', 'family' => 'Spectral', 'italics' => true, 'stack' => '"Spectral", Georgia, serif' ),
		'fraunces'         => array( 'label' => 'Fraunces', 'family' => 'Fraunces', 'italics' => true, 'stack' => '"Fraunces", Georgia, serif' ),
		'merriweather'     => array( 'label' => 'Merriweather', 'family' => 'Merriweather', 'italics' => true, 'stack' => '"Merriweather", Georgia, serif' ),
		'inter'            => array( 'label' => 'Inter', 'family' => 'Inter', 'italics' => false, 'stack' => '"Inter", ui-sans-serif, system-ui, sans-serif' ),
		'source-sans-3'    => array( 'label' => 'Source Sans 3', 'family' => 'Source Sans 3', 'italics' => false, 'stack' => '"Source Sans 3", ui-sans-serif, system-ui, sans-serif' ),
		'lato'             => array( 'label' => 'Lato', 'family' => 'Lato', 'italics' => false, 'stack' => '"Lato", ui-sans-serif, system-ui, sans-serif' ),
		'open-sans'        => array( 'label' => 'Open Sans', 'family' => 'Open Sans', 'italics' => false, 'stack' => '"Open Sans", ui-sans-serif, system-ui, sans-serif' ),
		'roboto'           => array( 'label' => 'Roboto', 'family' => 'Roboto', 'italics' => false, 'stack' => '"Roboto", ui-sans-serif, system-ui, sans-serif' ),
		'dm-sans'          => array( 'label' => 'DM Sans', 'family' => 'DM Sans', 'italics' => false, 'stack' => '"DM Sans", ui-sans-serif, system-ui, sans-serif' ),
		'nunito-sans'      => array( 'label' => 'Nunito Sans', 'family' => 'Nunito Sans', 'italics' => false, 'stack' => '"Nunito Sans", ui-sans-serif, system-ui, sans-serif' ),
		'karla'            => array( 'label' => 'Karla', 'family' => 'Karla', 'italics' => false, 'stack' => '"Karla", ui-sans-serif, system-ui, sans-serif' ),
	);
}

function inkwell_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'inkwell_options',
		array(
			'title'    => __( 'Inkwell Options', 'inkwell' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'inkwell_hero_kicker',
		array(
			'default'           => __( 'Personal blog', 'inkwell' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'inkwell_hero_kicker',
		array(
			'label'   => __( 'Homepage eyebrow text', 'inkwell' ),
			'section' => 'inkwell_options',
			'type'    => 'text',
		)
	);

	$font_choices = wp_list_pluck( inkwell_google_fonts(), 'label' );

	$wp_customize->add_setting(
		'inkwell_heading_font',
		array(
			'default'           => 'system-serif',
			'sanitize_callback' => 'inkwell_sanitize_font',
		)
	);
	$wp_customize->add_control(
		'inkwell_heading_font',
		array(
			'label'   => __( 'Heading font', 'inkwell' ),
			'section' => 'inkwell_options',
			'type'    => 'select',
			'choices' => $font_choices,
		)
	);

	$wp_customize->add_setting(
		'inkwell_body_font',
		array(
			'default'           => 'system-serif',
			'sanitize_callback' => 'inkwell_sanitize_font',
		)
	);
	$wp_customize->add_control(
		'inkwell_body_font',
		array(
			'label'   => __( 'Paragraph font', 'inkwell' ),
			'section' => 'inkwell_options',
			'type'    => 'select',
			'choices' => $font_choices,
		)
	);
}
add_action( 'customize_register', 'inkwell_customize_register' );

function inkwell_sanitize_font( $value ) {
	$fonts = inkwell_google_fonts();
	return isset( $fonts[ $value ] ) ? $value : 'system-serif';
}

function inkwell_fonts() {
	$heading = get_theme_mod( 'inkwell_heading_font', 'system-serif' );
	$body    = get_theme_mod( 'inkwell_body_font', 'system-serif' );
	$fonts   = inkwell_google_fonts();

	$families = array();
	foreach ( array_unique( array( $heading, $body ) ) as $slug ) {
		if ( ! empty( $fonts[ $slug ]['family'] ) ) {
			$axis        = $fonts[ $slug ]['italics'] ? 'ital,wght@0,400;0,600;0,700;1,400' : 'wght@400;600;700';
			$families[]  = 'family=' . str_replace( ' ', '+', $fonts[ $slug ]['family'] ) . ':' . $axis;
		}
	}

	if ( $families ) {
		wp_enqueue_style( 'inkwell-google-fonts', 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap', array(), INKWELL_VERSION );
	}

	$heading_font = isset( $fonts[ $heading ] ) ? $fonts[ $heading ]['stack'] : $fonts['system-serif']['stack'];
	$body_font    = isset( $fonts[ $body ] ) ? $fonts[ $body ]['stack'] : $fonts['system-serif']['stack'];

	wp_add_inline_style( 'inkwell-app', ':root{--font-serif:' . $heading_font . ';--font-body:' . $body_font . ';}' );
}
add_action( 'wp_enqueue_scripts', 'inkwell_fonts', 11 );

function inkwell_font_preconnect() {
	$heading = get_theme_mod( 'inkwell_heading_font', 'system-serif' );
	$body    = get_theme_mod( 'inkwell_body_font', 'system-serif' );
	$fonts   = inkwell_google_fonts();

	if ( ! empty( $fonts[ $heading ]['family'] ) || ! empty( $fonts[ $body ]['family'] ) ) {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'inkwell_font_preconnect', 1 );
