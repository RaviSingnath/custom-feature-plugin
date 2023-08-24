<?php

global $wpdb;

$query = $wpdb->prepare("
    SELECT DISTINCT post_content
    FROM $wpdb->posts
    WHERE post_type LIKE %s
", 'acf-post-type');

$results = $wpdb->get_results($query, ARRAY_A);
$custom_post_types = array();

foreach ($results as $result) {
    $custom_post_types[] = $result['post_content'];
}

$secondSerials = array();

foreach ($custom_post_types as $element) {
    $unserialized = unserialize($element);


    $secondSerial = $unserialized['post_type'];
    $secondSerials[] = $secondSerial;

}
$post_types1 = $secondSerials;

global $my_cpts;
$my_cpts = $post_types1;





global $wpdb;

// Query to retrieve all post types from wp_posts table
$query = "
    SELECT DISTINCT post_type
    FROM {$wpdb->posts}
";

$post_types2 = $wpdb->get_results( $query, ARRAY_A);
$custom_post_types = array();

foreach ($post_types2 as $post_type2) {
    $custom_post_types[] = $post_type2['post_type'];
}
$post_types2 = $custom_post_types;

$mergedArray = array_merge($post_types1, $post_types2);
$post_types = array_unique($mergedArray, SORT_REGULAR);
global $my_post_types;
$my_post_types = $post_types;