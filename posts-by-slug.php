<?php
/**
 * Plugin Name: Posts by Slug
 * Plugin URI: https://github.com/reneecampanilla/posts-by-slug
 * Description: Adds a Rest API endpoint to WordPress that returns post by slug.
 * Version: 1.0
 * Author: Renee Stanforth
 * Author URI: http://www.reneecampanilla.com
 */

function get_post_by_slug() {
	register_rest_route('wp/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'post_single',
    ]);
}

add_action( 'rest_api_init', 'get_post_by_slug' );


function post_single($slug) {

    $args = [
        'name' => $slug['slug'],
        'post_type' => 'post'
    ];

    $post = get_posts($args);

    $url = home_url() . "/wp-json/wp/v2/posts/" . $post[0]->ID;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    $result=curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);

}