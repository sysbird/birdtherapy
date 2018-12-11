<?php
/**
 * The template functions and definitions
 *
 * @package WordPress
 * @subpackage BirdTHERAPY
 * @since BirdTHERAPY 1.0
 */

//////////////////////////////////////////
// Set the content width based on the theme's design and stylesheet.
function birdtherapy_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'birdtherapy_content_width', 930 );
}
add_action( 'template_redirect', 'birdtherapy_content_width' );

//////////////////////////////////////////////////////
// XML-RPC disable
add_filter( 'xmlrpc_enabled', '__return_false');
if ( function_exists( 'add_filter' ) ) {
	add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );
}
function remove_xmlrpc_pingback_ping($methods)
{
	unset($methods['pingback.ping']);
	return $methods;
}

//////////////////////////////////////////////////////
// Comment disable
add_filter( 'comments_open', '__return_false' );

//////////////////////////////////////////////////////
// emoji disable
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

//////////////////////////////////////////////////////
// remove theme customize
function birdtherapy_customize_register( $wp_customize ) {
	$wp_customize->remove_section( 'static_front_page' );
	$wp_customize->remove_section( 'custom_css' );
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'title_tagline' );
}
add_action( 'customize_register', 'birdtherapy_customize_register' );

//////////////////////////////////////////
// Set Widgets
function birdtherapy_widgets_init() {

	// Widget Area for footer first column
	register_sidebar( array (
		'name'			=> __( 'Widget Area for footer first column', 'birdsite' ),
		'id'			=> 'widget-area-footer-left',
		'description'	=> __( 'Widget Area for footer first column', 'birdsite' ),
		'before_widget'	=> '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3>',
		'after_title'	=> '</h3>',
		) );

	// Widget Area for footer center column
	register_sidebar( array (
		'name'			=> __( 'Widget Area for footer center column', 'birdsite' ),
		'id'			=> 'widget-area-footer-center',
		'description'	=> __( 'Widget Area for footer center column', 'birdsite' ),
		'before_widget'	=> '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3>',
		'after_title'	=> '</h3>',
		) );

	// Widget Area for footer last column
	register_sidebar( array (
		'name'			=> __( 'Widget Area for footer last column', 'birdsite' ),
		'id'			=> 'widget-area-footer-right',
		'description'	=> __( 'Widget Area for footer last column', 'birdsite' ),
		'before_widget'	=> '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3>',
		'after_title'	=> '</h3>',
		) );
}
add_action( 'widgets_init', 'birdtherapy_widgets_init' );

//////////////////////////////////////////////////////
// Setup Theme
function birdtherapy_setup() {

	// Set languages
	load_theme_textdomain( 'birdtherapy', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Set feed
	add_theme_support( 'automatic-feed-links' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	/*
	 * Switch default core markup for search form
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'gallery',
		'caption',
	));

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Navigation Menu', 'birdtherapy' ),
	));

	// Add support for title tag.
	add_theme_support( 'title-tag' );

	// Add support for custom headers.
	add_theme_support( 'custom-header', array(
		'default-image'		=> get_parent_theme_file_uri( '/images/header.jpg' ),
		'height'			=> 900,
		'width'				=> 1280,
		'flex-height'		=> true,
	));

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/images/header.jpg',
			'thumbnail_url' => '%s/images/header.jpg',
			'description'   => __( 'Default Header Image', 'birdtherapy' ),
		),
	) );
}
add_action( 'after_setup_theme', 'birdtherapy_setup' );

//////////////////////////////////////////////////////
// Add custom post type
function birdtherapy_init() {

	//	 add tags at page
	register_taxonomy_for_object_type('post_tag', 'page');

	// add post type news
	$labels = array(
		'name'		=> 'お知らせ',
		'all_items'	=> 'お知らせの一覧',
		);

	$args = array(
		'labels'			=> $labels,
		'supports'			=> array( 'title','editor', 'thumbnail', 'custom-fields' ),
		'public'			=> true,	// 公開するかどうが
		'show_ui'			=> true,	// メニューに表示するかどうか
		'menu_position'		=> 5,		// メニューの表示位置
		'has_archive'		=> true,	// アーカイブページの作成
		);

	register_post_type( 'news', $args );
}
add_action( 'init', 'birdtherapy_init', 0 );

//////////////////////////////////////////////////////
// set fefault taxonomy for works
function birdtherapydefault_taxonomy_works() {
	echo '<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function($){
		// default check
		if ($("#works-catchecklist.categorychecklist input[type=checkbox]:checked").length == 0) {
		  $("#works-catchecklist.categorychecklist li:first-child input:first-child").attr("checked", "checked");
		}
	});
	//]]>
	</script>';
}
add_action( 'admin_print_footer_scripts', 'birdtherapydefault_taxonomy_works' );

//////////////////////////////////////////////////////
// Enable custom post type in Bogo
function birdtherapy_bogo_localizable_post_types( $localizable ) {
	$args = array(
		'public'   => true,
		'_builtin' => false
	);
	$custom_post_types = get_post_types( $args );
	return array_merge( $localizable, $custom_post_types );
}
add_filter( 'bogo_localizable_post_types', 'birdtherapy_bogo_localizable_post_types', 10, 1 );

//////////////////////////////////////////////////////
// Filter main query at home
function birdtherapy_home_query( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		// toppage information
		 $query->set( 'posts_per_page', 3 );
	}
	else if ( !is_admin() && $query->is_post_type_archive( 'essay' ) && $query->is_main_query() ) {
		 $query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'birdtherapy_home_query' );

//////////////////////////////////////////////////////
// Enqueue Scripts
function birdtherapy_scripts() {

	// Google Fonts
	wp_enqueue_style( 'birdtherapy-google-font', '//fonts.googleapis.com/css?family=Open+Sans', false, null, 'all' );
	wp_enqueue_style( 'birdtherapy-google-font-ja', '//fonts.googleapis.com/earlyaccess/sawarabimincho.css', false, null, 'all' );

	// this
	wp_enqueue_script( 'birdtherapy', get_template_directory_uri() .'/js/birdtherapy.js', array( 'jquery' ), '1.11' );
	wp_enqueue_style( 'birdtherapy', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'birdtherapy_scripts' );

//////////////////////////////////////////////////////
// Santize a checkbox
function birdtherapy_sanitize_checkbox( $input ) {

	if ( $input == true ) {
		return true;
	} else {
		return false;
	}
}

///////////////////////////////////////////////////////
// Sanitize text
function birdtherapy_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

//////////////////////////////////////////////////////
// Removing the default gallery style
function birdtherapy_gallery_atts( $out, $pairs, $atts ) {

	$atts = shortcode_atts( array( 'size' => 'medium', ), $atts );
	$out['size'] = $atts['size'];

	return $out;
}
add_filter( 'shortcode_atts_gallery', 'birdtherapy_gallery_atts', 10, 3 );
add_filter( 'use_default_gallery_style', '__return_false' );

//////////////////////////////////////////////////////
// archive title
function birdtherapy_get_the_archive_title ( $title ) {

	$birdtherapy_title = preg_replace('/.*:\s+/', '', $title );

	return $birdtherapy_title;
};
add_filter( 'get_the_archive_title', 'birdtherapy_get_the_archive_title' );

//////////////////////////////////////////////////////
// Excerpt More
function birdtherapy_excerpt_more( $more ) {
	return ' ...<span class="more"><a href="'. esc_url( get_permalink() ) . '" >' . __( 'more', 'birdtherapy') . '</a></span>';
}
add_filter('excerpt_more', 'birdtherapy_excerpt_more');

//////////////////////////////////////////////////////
// photos slide in book
function birdtherapy_photos_slide () {
	$post_id = get_the_ID();

	// get relate post_id in Japanese post
	$post_id = birdtherapy_get_relate_post_id_in_japanese( $post_id );

	// photos in post
	$html = '';
	$html_cover = '';
	$pages = 0;
	$args = array( 'post_type'			=> 'attachment',
					'posts_per_page'	=> -1,
					'post_parent'		=> $post_id,
					'post_mime_type'	=> 'image',
					'orderby'			=> 'ID',
					'order'				=> 'ASC' );

	$images = get_posts( $args );
	if ( $images ) {
		foreach( $images as $image ){
			$pages++;
			$src = wp_get_attachment_url( $image->ID );
			$thumbnail = wp_get_attachment_image_src( $image->ID, 'large' );
			$html .= ' <a href="' .$src .'" data-fancybox="birdtherapy-photos-slide" data-caption="' .$image->post_title .'">page' .$pages .'</a>';
		}

		wp_reset_postdata();
	}

	// output
	if( $pages ){
		$html = '<div class="birdtherapy-photos-slide">' .$html .'<p><a href="#" class="birdtherapy-photos-slide-start">' .sprintf( __( 'show photos(%d pages)', 'birdtherapy' ), $pages ) .'</a></p></div>';
		echo $html;
	}
}

//////////////////////////////////////////////////////
//  get relate post_id in Japanese post
function birdtherapy_get_relate_post_id_in_japanese( $post_id ) {

	$locales = get_locale(); 
	$pid_loc = get_post_meta( $post_id, '_locale' );
	
	if ( 'ja' === $locales || ! @$pid_loc[0] ){
		// no locale or Japanese
		return $post_id;
	} 

    $translations = bogo_get_post_translations( $post_id );
	foreach ( $translations as $key => $value ) {
		if ( 'ja' === $key ){
			// post in Japanese
			return $value->ID;
		  }
    }

	// no post in Japanese
	return $post_id;
}

//////////////////////////////////////////////////////
// Bogo 
remove_shortcode( 'bogo', 'bogo_language_switcher' );
add_shortcode( 'bogo', 'birdtherapy_language_switcher' );
function birdtherapy_language_switcher( $args = '' ) {

	$args = wp_parse_args( $args, array(
		'echo' => false,
	) );

	$links = bogo_language_switcher_links( $args );
	$output = '';

	foreach ( $links as $link ) {
		$class = array();
		$class[] = bogo_language_tag( $link['locale'] );
		$class[] = bogo_lang_slug( $link['locale'] );

		if ( get_locale() == $link['locale'] ) {
			$class[] = 'current';
		}

		$class = implode( ' ', array_unique( $class ) );

		$label = $link['native_name'] ? $link['native_name'] : $link['title'];

		if( 'ja' === $link['locale']){
			$label = '日本語';
		}
		elseif( 'en_US' === $link['locale']){
			$label = 'English';
		}			

		$title = $link['title'];

		if ( empty( $link['href'] ) ) {
			$li = '<span>' .esc_html( $label ) .'</span>';
		} else {
			$li = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $link['href'] ),
				esc_html( $label ) );
		}

		$li = sprintf( '<li class="%1$s">%2$s</li>', $class, $li );
		$output .= $li . "\n";
	}

	$output = '<ul class="bogo-language-switcher">' . $output . '</ul>' . "\n";

	$output = apply_filters( 'bogo_language_switcher', $output, $args );

	if ( $args['echo'] ) {
		echo $output;
	} else {
		return $output;
	}
}

//////////////////////////////////////////
//  Show custom field by ACF
function birdtherapy_the_custom_field( $ID, $selector, $before, $after ) {

	$value = get_field( $selector, $ID );
	if( !empty( $value ) ){
		echo $before .$value .$after;
	}
}

//////////////////////////////////////////////////////
// Add hook content begin
function birdtherapy_content_header() {

	// bread crumb
	$birdtherapy_html = '';

	if( !is_home()){
		if ( class_exists( 'WP_SiteManager_bread_crumb' ) ) {
			$birdtherapy_html .= '<div class="container">';
			$birdtherapy_html .= WP_SiteManager_bread_crumb::bread_crumb( array( 'echo'=>'false', 'home_label' => 'ホーム', 'search_label' =>  '「%s」の検索結果', 'elm_class' => 'bread_crumb' ));
			$birdtherapy_html .= '</div>';
		}
	}

	echo $birdtherapy_html;
}

//////////////////////////////////////////////////////
// Add hook content end
function birdtherapy_content_footer() {
}

//////////////////////////////////////////////////////
// Google Analytics
function birdtherapy_wp_head() {
	if ( !is_user_logged_in() ) {
		get_template_part( 'google-analytics' );
	}
}
add_action( 'wp_head', 'birdtherapy_wp_head' );

//////////////////////////////////////////////////////
// image optimize
function birdtherapy_handle_upload( $file )
{
	if( $file['type'] == 'image/jpeg' ) {
		$image = wp_get_image_editor( $file[ 'file' ] );

		if (! is_wp_error($image)) {
			$exif = exif_read_data( $file[ 'file' ] );
			$orientation = $exif[ 'Orientation' ];

			if (! empty($orientation)) {
				switch ($orientation) {
					case 8:
						$image->rotate( 90 );
						break;

					case 3:
						$image->rotate( 180 );
						break;

					case 6:
						$image->rotate( -90 );
						break;
				}
			}
			$image->save( $file[ 'file' ]) ;
		}
	}

	return $file;
}
add_action( 'wp_handle_upload', 'birdtherapy_handle_upload' );

//////////////////////////////////////////////////////
// Check postdate Recently
function birdtherapy_is_recently() {
	if( strtotime( get_the_date('Y-m-d' )) < strtotime( '2006-01-01' )){
		return false;
	}

	return true;
}

//////////////////////////////////////////////////////
// Santize a checkbox
function birdtherapy_sanitize_radiobutton( $input ) {

	if ( $input === 'light' ) {
		return $input;
	} else {
		return 'dark';
	}
}

//////////////////////////////////////////////////////
// Header Slider
function birdtherapy_headerslider() {

	if (( !is_front_page())) {
		return false;
	}

	$birdtherapy_slides = array();
	$birdtherapy_max = 0;

	$headers = get_uploaded_header_images();
	if($headers) {
		// many omage
		shuffle ( $headers );
		foreach ( $headers as $header ) {
			$birdtherapy_slides[ $birdtherapy_max ] = $header[ 'url' ];
			$birdtherapy_max++;
			if( 3 == $birdtherapy_max ){
				break;
			}
		}
	}
	else {
		// one omage
		$header_image = get_header_image();
		if( $header_image ){
			$birdtherapy_slides[ 0 ] = $header_image;
			$birdtherapy_max = 1;
		}
	}

	if( !$birdtherapy_max ){
		return false;
	}

	$slider_class = '';
	if( 1 < $birdtherapy_max ){
		$slider_class = ' slider';
	}

	?>

	<section id="wall">
		<div class="headerimage <?php echo $slider_class ?>" data-interval="7000">

<?php
	// sort randam
	$birdtherapy_html = '';
//	$birdtherapy_start = mt_rand( 1, $birdtherapy_max );
	for( $birdtherapy_count = 1; $birdtherapy_count <= $birdtherapy_max; $birdtherapy_count++ ) {
			$birdtherapy_class = '';
			if( 1 == $birdtherapy_count ){
				$birdtherapy_class = ' start active';
			}

			$birdtherapy_html .= '<div class="slideitem' .$birdtherapy_class .'" id="slideitem_' .$birdtherapy_count .'">';
			$birdtherapy_html .= '<div class="fixedimage" style="background-image: url(' .$birdtherapy_slides[ $birdtherapy_count -1 ] .')"></div>';
			$birdtherapy_html .= '</div>';
	}

	echo $birdtherapy_html;
?>
		</div>
	</section>
<?php

	return true;
}

function _birdtherapy_headerslider() {

	if (( !is_front_page())) {
		return false;
	}

	$birdtherapy_interval = get_theme_mod( 'slide_interval', 7000 );
	if( 0 == $birdtherapy_interval){
		$birdtherapy_interval = 7000;
	}

	// get headerslide option
	$birdtherapy_slides = array();
	$birdtherapy_max = 0;

	for( $birdtherapy_count = 1; $birdtherapy_count <= 5; $birdtherapy_count++ ) {
		$birdtherapy_default_image = '';
		$birdtherapy_default_title = '';
		$birdtherapy_default_description = '';
		$birdtherapy_default_link = '';

		if( 1 == $birdtherapy_count ){
			$birdtherapy_default_image = get_template_directory_uri() . '/images/header.jpg';
			$birdtherapy_default_title =  __( 'Hello world!','birdtherapy' );
			$birdtherapy_default_description = __( 'Begin your website.', 'birdtherapy' );
			$birdtherapy_default_link = '#';
		}

		$birdtherapy_image = get_theme_mod( 'slider_image_' .strval( $birdtherapy_count ), $birdtherapy_default_image );
		if ( ! empty( $birdtherapy_image )) {
			$birdtherapy_slides[ $birdtherapy_count -1 ][ 'image' ] = $birdtherapy_image;
			$birdtherapy_slides[ $birdtherapy_count -1 ][ 'title' ] = get_theme_mod( 'slider_title_' . strval( $birdtherapy_count ), $birdtherapy_default_title );
			$birdtherapy_slides[ $birdtherapy_count -1 ][ 'description' ] = get_theme_mod( 'slider_description_' . strval( $birdtherapy_count ), $birdtherapy_default_description );
			$birdtherapy_slides[$birdtherapy_count -1 ][ 'link' ] = get_theme_mod( 'slider_link_' . strval( $birdtherapy_count ), $birdtherapy_default_link );

			$birdtherapy_max++;
		}
		else{
			break;
		}
	}

	if( !$birdtherapy_max ){
		return false;
	}

?>
	<section id="wall">
		<div class="headerimage slider" data-interval="<?php echo $birdtherapy_interval; ?>">

<?php
	// sort randam
	$birdtherapy_html = '';
	$birdtherapy_start = mt_rand( 1, $birdtherapy_max );
	for( $birdtherapy_count = 1; $birdtherapy_count <= $birdtherapy_max; $birdtherapy_count++ ) {
			$birdtherapy_class = '';
			if( $birdtherapy_start == $birdtherapy_count ){
				$birdtherapy_class = ' start active';
			}

			$birdtherapy_html .= '<div class="slideitem' .$birdtherapy_class .'" id="slideitem_' .$birdtherapy_count .'">';
			$birdtherapy_html .= '<div class="fixedimage" style="background-image: url(' .$birdtherapy_slides[ $birdtherapy_count -1 ][ 'image' ] .')"></div>';
			$birdtherapy_html .= '<div class="caption">';
			$birdtherapy_html .= '<p><strong>' .$birdtherapy_slides[ $birdtherapy_count -1 ][ 'title' ] .'</strong><span>' .$birdtherapy_slides[ $birdtherapy_count -1 ][ 'description' ] .'</span></p>';
			if( ! empty( $birdtherapy_slides[ $birdtherapy_count -1 ][ 'link' ] )){
				$birdtherapy_html .= '<a href="' .$birdtherapy_slides[ $birdtherapy_count -1 ][ 'link' ] .'">' .__( 'More', 'birdtherapy' ) .'</a>';
			}
			$birdtherapy_html .= '</div>';
			$birdtherapy_html .= '</div>';
	}

	echo $birdtherapy_html;
?>
		</div>
	</section>

<?php
	return true;
}