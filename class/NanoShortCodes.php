<?php

class CustomCompanyAddonNanoShortCodes
{
    public function __construct()
    {
        self::get_verification_badge();
        $this->rating_filter_box();
    }

    /**
     * Verification badge
     * shortcode: [get_verification_badge]
     * @return void
     */
    public static function get_verification_badge()
    {
        add_shortcode("get_verification_badge", function () {
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

    /**
     * Comment Filter Box
     * shortcode: [get_comment_filters]
     * @return void
     */
    public function rating_filter_box()
    {
        add_shortcode("get_comment_filters", function () {
            $comment_ids = $this->get_comment_ids_by_post_id(get_the_ID());
            $all_ratings = [];
            foreach ($comment_ids as $comment_ID) {
                $custom_meta_value = get_comment_meta($comment_ID, 'rating', true);
                $all_ratings[] = $custom_meta_value;
            }

            // categorized ratings
            $one_star = array_filter($all_ratings, fn($rating) => intval($rating) === 1);
            $two_star = array_filter($all_ratings, fn($rating) => intval($rating) === 2);
            $three_star = array_filter($all_ratings, fn($rating) => intval($rating) === 3);
            $four_star = array_filter($all_ratings, fn($rating) => intval($rating) === 4);
            $five_star = array_filter($all_ratings, fn($rating) => intval($rating) === 5);
            $total_number_of_ratings = count($one_star) + count($two_star) + count($three_star) + count($four_star) + count($five_star);

            // percentages of stars
            $percentage_of_one_star = round((count($one_star) * 100) / $total_number_of_ratings, 2);
            $percentage_of_two_star = round((count($two_star) * 100) / $total_number_of_ratings, 2);
            $percentage_of_three_star = round((count($three_star) * 100) / $total_number_of_ratings, 2);
            $percentage_of_four_star = round((count($four_star) * 100) / $total_number_of_ratings, 2);
            $percentage_of_five_star = round((count($five_star) * 100) / $total_number_of_ratings, 2);

            ?>

            <div class="custom_company_addon_rating_filter_progressbar">
                <div>
                    <div>
                        <span>1-Star</span>
                    </div>
                    <div>
                        <progress value="<?php echo count($one_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($one_star) ?></progress>
                    </div>
                    <div>
                        <span><?php echo $percentage_of_one_star; ?>%</span>
                    </div>
                </div>
                <div>
                    <div>
                        <span>2-Star</span>
                    </div>
                    <div>
                        <progress value="<?php echo count($two_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($two_star) ?></progress>
                    </div>
                    <div>
                        <span><?php echo $percentage_of_two_star; ?>%</span>
                    </div>
                </div>
                <div>
                    <div>
                        <span>3-Star</span>
                    </div>
                    <div>
                        <progress value="<?php echo count($three_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($three_star) ?></progress>
                    </div>
                    <div>
                        <span><?php echo $percentage_of_three_star; ?>%</span>
                    </div>
                </div>
                <div>
                    <div>
                        <span>4-Star</span>
                    </div>
                    <div>
                        <progress value="<?php echo count($four_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($four_star) ?></progress>
                    </div>
                    <div>
                        <span><?php echo $percentage_of_four_star; ?>%</span>
                    </div>
                </div>
                <div>
                    <div>
                        <span>5-Star</span>
                    </div>
                    <div>
                        <progress value="<?php echo count($five_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($five_star) ?></progress>
                    </div>
                    <div>
                        <span><?php echo $percentage_of_five_star; ?>%</span>
                    </div>
                </div>
            </div>

            <?php
        });
    }

    /**
     * Get All Comment Ids
     * @param $post_id
     * @return array
     */
    private function get_comment_ids_by_post_id($post_id)
    {
        // Fetch comments for the given post ID
        $comments = get_comments(array(
            'post_id' => $post_id,
            'status' => 'approve', // You can change this to 'all' if you want to fetch all comments regardless of their status
            'type' => 'comment', // You can change this to 'pings' if you want to fetch pingbacks and trackbacks
        ));

        // Extract comment IDs
        $comment_ids = wp_list_pluck($comments, 'comment_ID');

        return $comment_ids;
    }
}