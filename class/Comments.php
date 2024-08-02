<?php

class CustomCompanyAddonComment {
    public function  __construct()
    {
        add_filter('query_vars', [$this, 'custom_register_query_vars']);
//        add_action('pre_get_comments', [$this, 'custom_filter_comments_by_meta']);
    }
    public function custom_register_query_vars($vars) {
        $vars[] = 'rating';
        return $vars;
    }

//    public function custom_filter_comments_by_meta($comments_query) {
//        if (is_singular('company') && get_query_var('rating')) {
//            $meta_value = get_query_var('rating');
//            $comments_query->query_vars['meta_key'] = 'rating';
//            $comments_query->query_vars['meta_value'] = $meta_value;
//        }
//    }
}