<?php

/**
 * Producers Custom Post
 */
function create_producer_cpt() {
    $labels = array(
        'name' => 'Производители',
        'singular_name' => 'Производитель',
        'menu_name' => 'Производители',
        'add_new' => 'Добавить Производителя',
        'add_new_item' => 'Добавить Производителя',
        'all_items' => 'Все Производители'
    );
    $args = array(
        'public' => true,
        'query_var' => true,
        'publicly_queryable' => true,
        'labels' => $labels,
        'show_ui' => true,
        'menu_icon' => 'dashicons-category',
        'show_in_menu' => true,
        'rewrite' => array( 'slug' => 'manufacturers' ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'supports' => array( 'title', 'editor')
    );
    register_post_type( 'manufacturer', $args );
}
add_action( 'init', 'create_producer_cpt' );

/**
 * Fix 404 error for pagination
 */
function prefix_change_cpt_archive_per_page( $query ) {
    if ( $query->is_main_query() && ! is_admin() && is_post_type_archive( 'manufacturer' ) ) {
        $query->set( 'posts_per_page', '2' );
    }
}
add_action( 'pre_get_posts', 'prefix_change_cpt_archive_per_page' );