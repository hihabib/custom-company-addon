<?php

class CustomCompanyAddonNanoShortCodes
{
    public function __construct()
    {
        self::get_review_quantities();
        self::get_verification_badge();
    }

    /**
     * Get Review description
     * shortcode: [get_comments_number]
     * @return void
     */
    public static function get_review_quantities(){
        add_shortcode("get_comments_number", function(){
            echo "<div class='review-description-header-custom-company-addon'> Reviews " . get_comments_number() . "</div>";
        });
    }

    /**
     * Verification badge
     * shortcode: [get_verification_badge]
     * @return void
     */
    public static function get_verification_badge(){
        add_shortcode("get_verification_badge", function(){
            $is_verified = get_field('verified');

            echo $is_verified ? "<div class='custom-company-addon-verification-badge'>
                    <div> 
                        <svg style='transform: scale(1.4)' viewBox='0 0 16 16' fill='green' class='icon_icon__ECGRl icon_appearance-positive__4px2T' xmlns='http://www.w3.org/2000/svg' width='12px' height='12px'><path class='ic-verified-user' d='M1 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v7.989a2 2 0 0 1-1.298 1.873l-5 1.875c-.453.17-.951.17-1.404 0l-5-1.875A2 2 0 0 1 1 10.989V3Z'></path><path style='fill: white' class='ic-verified-user-check' d='M11.618 6.12a.875.875 0 1 0-1.236-1.24L7.03 8.22 5.66 6.647a.875.875 0 0 0-1.32 1.15l1.768 2.03c.041.047.086.089.135.125a.875.875 0 0 0 1.364.163l4.01-3.995Z'></path></svg>
                    </div>
                    <div style='margin-top: -3px'>Verified Company</div>
                    <div></div>
                 </div>" : "";
        });
    }
}