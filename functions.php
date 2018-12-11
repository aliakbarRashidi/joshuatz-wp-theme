<?php
/**
 * Setup resources
 */
$debug = true;
$themeRootURL = get_template_directory_uri();
$themeLibURL = (get_template_directory_uri().'/lib');
$themeIncURL = (get_template_directory_uri().'/inc');
$themeIncPath = (get_template_directory() . '/inc');

/**
 * Load required files
 */
require_once($themeIncPath . '/taxonomies.php');
require_once($themeIncPath . '/custom-post-types.php');
require_once($themeIncPath . '/custom-theme-settings.php');
require_once($themeIncPath . '/helpers.php');

/**
 * Make sure helpers is loaded globally
 */
global $jtzwpHelpers;
$jtzwpHelpers = (gettype($jtzwpHelpers)==='object' ? $jtzwpHelpers : new JtzwpHelpers());

/**
 * Special WP flags
 * https://codex.wordpress.org/Option_Reference
 * https://codex.wordpress.org/Theme_Features
 */
// "feature image" support
add_theme_support('post-thumbnails'); 
// Allow excerpts for pages
add_post_type_support('page','excerpt');

/**
 * Special WP Cleanup
 */
remove_action( 'wp_head', 'wp_generator' ) ;
remove_action( 'wp_head', 'wlwmanifest_link' ) ;
remove_action( 'wp_head', 'rsd_link' ) ;

/**
 * Reusable loaders
 */

function joshuatzwp_styles() {
    global $themeLibURL,$themeRootURL,$jtzwpHelpers;
    if ($jtzwpHelpers->isPageWP()){
        // Materialize CSS
        wp_enqueue_style('materialize-style',$themeLibURL.'/materialize/css/materialize.min.css',array(),false,'all');
        // Materialize Icon Set
        wp_enqueue_style('materialize-icons','https://fonts.googleapis.com/icon?family=Material+Icons',array(),false,'all');
    }
    // Load main theme CSS file (style.css)
    wp_enqueue_style('joshuatzwp-style',get_stylesheet_uri(),array('materialize-style'),false,'all');
    // Load final <head></head> style
    wp_enqueue_style('style-final-head',$themeRootURL.'/style-final-head.css',array('joshuatzwp-style','materialize-style'),false,'all');
}

function joshuatzwp_styles_deferred(){
    global $themeRootURL;
    // Font Awesome - defer OK
    wp_enqueue_style('font-awesome-style','https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css',array(),false,'all');
    // Google Fonts - defer OK
    wp_enqueue_style('google-fonts','https://fonts.googleapis.com/css?family=Lato',array(),false,'all');
    // animate.css - defer OK
    wp_enqueue_style('animate-css','https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css',array(),false,'all');
    // style-deferred.css - defer DESIRED
    wp_enqueue_style('style-deferred',$themeRootURL.'/style-deferred.css',array(),false,'all');
    // Lightbox 2 - Lokesh - Defer OK
    // sha256-auPoJwk/+RK6KSkib92Dkq1Y5hEkZvKtvSwucs15Skg=
    //wp_enqueue_style('lightbox-2-css','https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.min.css',array(),false,'all');
    // Fancybox 3 - Defer OK
    // sha256-5yrE3ZX38R20LqA/1Mvh3KHJWG1HJF42qtZlRtGGRgE=
    wp_enqueue_style('fancybox3-style','https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.min.css',array(),false,'all');

}

function joshuatzwp_styles_for_admin(){
    global $themeRootURL;
    wp_enqueue_style('admin-styles', $themeRootURL.'/admin.css',array(),false,'all');
}

function joshuatzwp_scripts() {
    global $themeLibURL;
    global $themeIncPath;
    global $themeRootURL;
    wp_enqueue_script('jquery-3','https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',array(),false,false);
    // Materialize JS
    wp_enqueue_script('materialize-js',$themeLibURL.'/materialize/js/materialize.min.js',array('jquery-3'),false,false);
    // Main JS
    wp_enqueue_script('main-js',$themeRootURL.'/main.js',array('jquery-3','materialize-js'),false,true);
    // wow.js
    wp_enqueue_script('wow-js','https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js',array(),false,true);
}

function joshuatzwp_scripts_deferred(){
    global $themeRootUrl;
    // Lightbox 2 - Lokesh
    // sha256-DiHJ7hbvMejsMyP76bpVWacb5HSHQ2sQlrJV8n7KEvA=
    //wp_enqueue_script('lightbox-2-js','https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.min.js',array('jquery-3'),false,true);
    // Fancybox 3
    // sha256-ULR2qlEu6WigJY4xQsDsJeW76e9tEE2EWjnKEQ+0L8Q=
    wp_enqueue_script('fancybox3-js','https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.min.js',array('jquery-3'),false,true);
}

function joshuatzwp_scripts_admin(){
    global $themeRootURL;
    // admin.js
    wp_enqueue_script('admin-script',$themeRootURL.'/admin.js',array('jquery'),false,true);
}

function joshuatzwp_enqueue_loader_head() {
    // Load styles
    joshuatzwp_styles();
    // Load scripts
    joshuatzwp_scripts();
}

function joshuatzwp_enqueue_loader_deferred(){
    // Load styles
    joshuatzwp_styles_deferred();
    // Load scripts
    joshuatzwp_scripts_deferred();
}

function joshuatzwp_enqueue_loader_admin(){
    // Load styles
    joshuatzwp_styles_for_admin();
    // Load scripts
    joshuatzwp_scripts_admin();
}

/**
 * Actual loader section
 */
// Load scripts and styles
add_action('wp_enqueue_scripts','joshuatzwp_enqueue_loader_head');
// Load scripts and styles - DEFERRED
add_action('wp_footer','joshuatzwp_enqueue_loader_deferred');
// Load scripts and styles - admin area
add_action('admin_enqueue_scripts','joshuatzwp_enqueue_loader_admin');

// Load custom post types
add_action('init','jtwp_register_all_custom_posttypes');

// Load custom taxonomies
add_action('init','jtwp_register_all_custom_taxonomies');
if ($debug){
    // REMOVEME
    //flush_rewrite_rules(false);
}

/**
 * Hook into wp_head for anything that needs to run first - useful for global includes
 */
function jtzwp_head_hook(){
    //
}
add_action('wp_head','jtzwp_head_hook');

/**
 * Hook into template_redirect for anything that needs to happen after query, but BEFORE headers are sent - e.g. redirects
 */
function jtwzp_template_redirect_hook(){
    global $jtzwpHelpers,$post;
    $currentUrl = $jtzwpHelpers->getCurrentUrl();
    if (is_singular()){
        if ($jtzwpHelpers->postOnlyLinksExternally($post)){
            wp_redirect($jtzwpHelpers->postOnlyLinksExternally($post));
            exit;
        }
    }
    // If path not found...
    else if (is_404()){
        $jtzwpHelpers->checkForAndHandleCustomRedirect($currentUrl);
    }
}

/**
 * Check whether or not the stored GAUID string is valid (at least based on pattern)
 */
function jtzwp_validate_gauid_setting(){
    $gauid = get_option('jtzwp_settings')['jtzwp_ga_gauid'];
    if ($gauid && preg_match('/UA-\d{8}-\d{1}/',$gauid)){
        return $gauid;
    }
    else {
        return false;
    }   
}

add_action('template_redirect','jtwzp_template_redirect_hook');

function jtzwp_get_disclaimer(){
    global $jtzwpHelpers;
    $disclaimer = false;
    $singularName = $jtzwpHelpers->getCustomPostTypeSingularName();
    $yearsOld = $jtzwpHelpers->getDateDiffByUnit($jtzwpHelpers->getPublishedDateDiff(get_post()),'years');
    $yearsOldDisclaimer = 'This ' . $singularName . ' is over a year old (first published about ' . $yearsOld . ' years ago). As such, please keep in mind that some of the information may no longer be accurate, best practice, or a reflection of how I would approach the same thing today.';
    if (get_field('show_disclaimer')===true){
        $customDisclaimer = get_field('custom_disclaimer');
        if (strlen($customDisclaimer)>1){
            $disclaimer = $customDisclaimer;
        }
        else if ($yearsOld > 1){
            $disclaimer = $yearsOldDisclaimer;
        }
    }
    else if ($yearsOld > 2){
        $disclaimer = $yearsOldDisclaimer;
    }
    return $disclaimer;
}

/**
 * Special Yoast SEO plugin settings
 */
function jtzwp_yoast_save_meta_val($postId,$metaKey,$metaVal){
    if (function_exists('wpseo_set_value')){
        add_action('wpseo_saved_postdata',function() use ($metaKey,$metaVal,$postId){
            update_post_meta($postId,$metaKey,$metaVal);
        },11);
    }
}
function jtzwp_yoast_var_replacement__jtzwp_description($varName){
    global $jtzwpHelpers;
    // Lower
    $metaDescription = '';
    if (is_single() && get_field('custom_seo_meta_description') && get_field('custom_seo_meta_description')!==''){
        $metaDescription = get_field('custom_seo_meta_description');
    }
    else if (!is_single() && term_description() && term_description()!==''){
        $metaDescription = strip_tags(term_description());
    }
    else if (!is_single() && get_post_type()===$jtzwpHelpers::PROJECTS_POST_TYPE){
        $customName = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_displayed_name');
        $name = $customName->isValid===true ? $customName->val : "this site's creator";
        $metaDescription = 'Projects using the ' . strtolower(single_cat_title('',false)) . ' skills of ' . $name;
    }
    else {
        $metaDescription = get_the_excerpt();
    }
    return $metaDescription;
}
function jtzwp_yoast_var_replacement__jtzwp_keywords($varName){
    global $jtzwpHelpers,$post;
    $keywordsCommaSep = '';
    if (get_field('custom_seo_keywords') && get_field('custom_seo_keywords')!==''){
        $keywordsCommaSep = get_field('custom_seo_keywords');
    }
    else if ($jtzwpHelpers->getTagsInfoArrs()->count > 0) {
        $keywordsCommaSep = $jtzwpHelpers->getTagsInfoArrs()->commaSep;
    }
    else if (strlen(WPSEO_Meta::get_value('focuskw',$post->ID)>0)){
        $keywordsCommaSep = WPSEO_Meta::get_value('focuskw',$post->ID);
    }
    return $keywordsCommaSep;
}
function jtzwp_register_yoast_extra_vars(){
    wpseo_register_var_replacement('%%jtzwp_description%%','jtzwp_yoast_var_replacement__jtzwp_description','advanced','Generated description based on ACF');
    wpseo_register_var_replacement('%%jtzwp_keywords%%','jtzwp_yoast_var_replacement__jtzwp_keywords','advanced','Generated keywords based on ACF and post');
}
add_action('wpseo_register_extra_replacements','jtzwp_register_yoast_extra_vars');

/**
 * Hooks on saving a post
 */
function jtzwp_after_post_edit($postId){
    global $jtzwpHelpers;
    // Immediately unhook this function to avoid infinite loops
    remove_action('save_post','jtzwp_after_post_edit');

    // Make sure ID is of real post and not revision
    $postId = (!wp_is_post_revision($postId)) ? $postId : $postId->post_parent;

    // Check to see if "NoIndex" flag should be applied since post is "thin content"
    /**
     * !!!-NOTE-!!! - Yoast no longer uses binary setting with _yoast_wpseo_meta-robots-noindex
     *       - If you want to turn off indexing (noindex) - the value should be '1'
     *       - When you turn indexing back ON (default setting) there are a few ways to do this
     *           - You can set the value to '2' - which equates to 'yes'
     *              OR - and this is what Yoast does if you change the setting yourself in the GUI back to default -
     *           - You can DELETE the meta value for the post, which tells Yoast that the post INHERITS the noindex setting from the post-type group. For example, if you delete the meta value for a custom post type, that post will inherit the indexing setting applied for that specific custom post type group.
     *              - Deleting the meta key seems the safest route, so that way you can easily toggle the default setting and still affect all the posts you want to.
     */

    // You need to wrap any code that touches the Yoast meta key value pairs in add_action() callback for 'wpseo_saved_postdata' - since that fires after Yoast updates the meta values, and if you touch the values BEFORE it fires, Yoast will simply overwrite what you changed!
    add_action('wpseo_saved_postdata',function() use ($metaKey,$metaVal,$postId,$jtzwpHelpers){
        update_post_meta($postId,$metaKey,$metaVal);
        if ($jtzwpHelpers->shouldPostBeIndexed($postId) === false){
            // Note, this also removes the post from the XML sitemap generated by Yoast
            update_post_meta($postId,'_yoast_wpseo_meta-robots-noindex','1');
        }
        else {
            // See note above about special Yoast noindex meta values
            //update_post_meta($postId,'_yoast_wpseo_meta-robots-noindex','2');
            delete_post_meta($postId,'_yoast_wpseo_meta-robots-noindex');
        }
    },11);
    $jtzwpHelpers->log('gettype($postId) = ' . gettype($postId));

    add_action('save_post','jtzwp_after_post_edit');

}
add_action('save_post','jtzwp_after_post_edit');