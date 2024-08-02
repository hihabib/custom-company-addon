<?php

class CustomCompanyAddonComment
{
    public function __construct()
    {
        add_filter('query_vars', [$this, 'custom_register_query_vars']);
        add_action('init', function () {
            $this -> comments_template();
            $this -> add_new_comment();
        });
    }

    public function custom_register_query_vars($vars)
    {
        $vars[] = 'rating';
        return $vars;
    }

    public function add_new_comment(){
        add_shortcode('add_new_comment', function(){
            comment_form();
        });
    }

    public function comments_template()
    {
        add_shortcode("custom_company_comments_template", function () {
            $args = [
                'post_id' => get_the_ID(),
            ];
            if (is_singular('company') && get_query_var('rating')) {
                $args['meta_query'] = [
                    [
                        'key' => 'rating',
                        'value' => explode(",",get_query_var('rating')),
                        'compare' => 'IN'
                    ]
                ];

            }
            $comment_query = new WP_Comment_Query($args);
            $comments = $comment_query->comments;
            echo "<ul class='company-reviews'>";
            wp_list_comments([
                'style' => 'ul'
            ], $comments);
            echo "<ul>";
        });
    }

}