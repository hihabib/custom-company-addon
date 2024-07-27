<?php

class CustomCompanyAddonNanoShortCodes
{
    public function __construct()
    {
        self::get_review_quantities();
    }

    /**
     * Get Review description
     * shortcode: [get_comments_number]
     * @return void
     */
    public static function get_review_quantities(){
        add_shortcode("get_comments_number", function(){
            echo "<div class='review-description-header-custom-company-addon'> Reviews" . get_comments_number() . "  •  </div>";
        });
    }
}