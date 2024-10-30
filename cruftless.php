<?
/*
 * Plugin Name: Cruftless
 * Description: Clean up the cruft in your HTML header. Eliminate the bloat and unnecessary resources that WordPress generates into your HTML.
 * Version:     0.0.1
 * Author:      Scott Thomason (scott8035)
 * Author URI:  https://www.linkedin.com/in/scottthomason
 * License:     MIT License
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: cruftless
 */

if ( ! defined( 'ABSPATH' ) ) { die; }

if ( ! is_admin() ) {
    add_action('wp_head', 'cruftless_cleanup_head', 5);
    add_action( 'init', 'cruftless_disable_wp_emojicons', 7);
    // add_action( 'init', 'cruftless_disable_admin_bar', 5 );
    add_action('after_setup_theme', 'cruftless_disable_comment_cookies', 5);
    add_action('after_setup_theme', 'cruftless_disable_edit_post', 5);
    add_action( 'wp_enqueue_scripts', 'cruftless_disable_wp_embed', 5);
    add_action( 'after_setup_theme', 'cruftless_remove_rest_api', 5);
    add_filter( 'redirect_canonical', 'cruftless_stop_guessing_url', 5 );
}

if ( ! function_exists( 'cruftless_cleanup_head' ) ) {
function cruftless_cleanup_head() {
    remove_action( 'wp_head', 'adjacent_posts_rel_link' );          // Remove relational links for the posts adjacent to the current post.
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );  // Dupe of shorter version above?
    remove_action( 'wp_head', 'feed_links', 2 );                    // Remove the links to the general feeds: Post and Comment Feed
    remove_action( 'wp_head', 'feed_links_extra', 3 );              // Remove the links to the extra feeds such as category feeds
    remove_action( 'wp_head', 'index_rel_link' );                   // index link
    remove_action( 'wp_head', 'parent_post_rel_link' );             // Remove prev link
    remove_action( 'wp_head', 'rest_output_link_wp_head' );         // Remove the REST API lines from the HTML Header
    remove_action( 'wp_head', 'rsd_link' );                         // Remove the link to the Really Simple Discovery service endpoint, EditURI link
    remove_action( 'wp_head', 'start_post_rel_link' );              // Remove the start link
    remove_action( 'wp_head', 'wlwmanifest_link' );                 // Remove the link to the Windows Live Writer manifest file.
    remove_action( 'wp_head', 'wp_generator' );                     // Remove the XHTML generator that is generated on the wp_head hook, WP version
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );    // Remove the alternate discovery links
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );            // Remove oEmbed-specific JavaScript from the front-end and back-end
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );             // Remove the condensed shortlink (like from wp.me)
}}


if ( ! function_exists( 'cruftless_disable_wp_emojicons' ) ) {
function cruftless_disable_wp_emojicons() {
    remove_action( 'admin_print_styles',  'print_emoji_styles' );
    remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles',     'print_emoji_styles' );
    remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
    remove_filter( 'embed_head',          'print_emoji_detection_script');
}}


if ( ! function_exists( 'cruftless_disable_admin_bar' ) ) {
function cruftless_disable_admin_bar() {
    add_filter('show_admin_bar', '__return_false');
}}


if ( ! function_exists( 'cruftless_disable_comment_cookies' ) ) {
function cruftless_disable_comment_cookies() {
    remove_action( 'set_comment_cookies', 'wp_set_comment_cookies');
}}


if ( ! function_exists( 'cruftless_disable_edit_post' ) ) {
function cruftless_disable_edit_post() {
    add_filter( 'edit_post_link', '__return_false' );
}}


if ( ! function_exists( 'cruftless_disable_wp_embed' ) ) {
function cruftless_disable_wp_embed() {
    wp_deregister_script('wp-embed');
}}


if ( ! function_exists( 'cruftless_remove_rest_api' ) ) {
function cruftless_remove_rest_api () {
    // Remove the REST API endpoint.
    remove_action( 'rest_api_init',         'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover',    '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse',      'wp_filter_oembed_result' );

    // Remove all embeds rewrite rules.
    if ( function_exists( 'disable_embeds_rewrites' ) ) {
        add_filter( 'rewrite_rules_array',  'disable_embeds_rewrites' );
    }

    // More found via debugger...
    remove_action( 'init',                  'rest_api_init' );
    remove_action( 'parse_request',         'rest_api_loaded' );
    remove_action( 'template_redirect',     'rest_output_link_header', 11 );
    remove_action( 'auth_cookie_valid',     'rest_cookie_collect_status' );
    remove_action( 'auth_cookie_malformed', 'rest_cookie_collect_status' );
}}


if ( ! function_exists( 'cruftless_stop_guessing_url' ) ) {
function cruftless_stop_guessing_url($url) {
    if ( is_404() ) {
        return false;
    }

    return $url;
}}

?>
