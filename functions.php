<?php

function university_files(){
    wp_enqueue_style('fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
    wp_enqueue_style('font','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    if(strstr($_SERVER['SERVER_NAME'],'localhost')){
        wp_enqueue_script('university_main_scripts','http://localhost:3000/bundled.js',null,'1.0',true);
    } else {
        wp_enqueue_script('our-vendors-js',get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'),null,'1.0',true);
        wp_enqueue_script('university_main_scripts',get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'),null,'1.0',true);
        wp_enqueue_style('our-main-styles',get_theme_file_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'));
    }
}

add_action('wp_enqueue_scripts','university_files');

function university_features(){
    add_theme_support('title-tag');
    // register_nav_menu('haaderMenuLocation','Header menu location');
    // register_nav_menu('footerLocationOne','Footer location one');
    // register_nav_menu('footerLocationTwo','Footer location two');
}

add_action('after_setup_theme','university_features');

function university_post_types(){
    register_post_type('event',array(
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'events',
        ),
        'supports' => array('title','editor','excerpt'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar',
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add new event',
            'edit_item' => 'Edit event',
            'all_items' => 'All events',
            'singular_name' => 'Event',
        ),
    ));
}

add_action('init','university_post_types');

function university_ajust_queries($query){
    if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()){
        $today = date('Ymd');
        $query->set('meta_key','event_date');
        $query->set('orderby','meta_value_num');
        $query->set('order','ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
            ),
        ));
    }
}

add_action('pre_get_posts','university_ajust_queries');