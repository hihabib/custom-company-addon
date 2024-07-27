<?php

class CustomCompanyAddonNanoShortCodes
{
    public function __construct()
    {
        self::get_review_quantities();
    }

    public static function get_review_quantities(){
        add_shortcode("get_comments_number", function(){
            echo get_comments_number();
        });
    }
}