<?php

class CustomCompanyAddonNanoShortCodes
{
    public function __construct()
    {
        self::get_verification_badge();
        $this->rating_filter_box();
        $this -> comments_template();
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
            <style>
                .custom_company_addon_rating_filter_percentage {
                    align-items: end;
                }

                .custom_company_addon_rating_filter {
                    display: flex;
                    column-gap: 20px
                }

                .custom_company_addon_rating_filter_star_text,
                .custom_company_addon_rating_filter_progressbar,
                .custom_company_addon_rating_filter_percentage {
                    display: flex;
                    justify-content: center;
                    flex-direction: column;
                }

                .custom_company_addon_rating_filter_star_text > *,
                .custom_company_addon_rating_filter_progressbar > *,
                .custom_company_addon_rating_filter_percentage > * {
                    padding: 0;
                    height: 30px;
                    display: block;
                }

                .custom_company_addon_rating_filter_percentage > * {
                    padding-top: 2px
                }

                .custom_company_addon_rating_filter_progressbar {
                    flex-grow: 1;
                }

                .custom_company_addon_rating_filter_progressbar progress {
                    width: 100%;
                }

                .custom_company_addon_rating_filter_progressbar > * {
                    display: flex;
                    align-items: center;
                    width: 100%
                }

                .custom_company_addon_rating_filter_progressbar progress {
                    border-radius: 15px;
                    height: 12px
                }

                .custom_company_addon_rating_filter_progressbar progress::-webkit-progress-bar {
                    background-color: rgb(241, 241, 232);
                    border-radius: 15px;
                }

                .custom_company_addon_rating_filter_progressbar progress::-webkit-progress-value {
                    background-color: rgb(28, 28, 28);
                    border-radius: 15px;
                }

                .custom_company_addon_rating_filter_progressbar progress::-moz-progress-bar {
                    background-color: rgb(241, 241, 232);
                    border-radius: 15px;
                }
            </style>
            <div class="custom_company_addon_rating_filter">
                <div class="custom_company_addon_rating_filter_star_text">
                    <span>5-Star</span>
                    <span>4-Star</span>
                    <span>3-Star</span>
                    <span>2-Star</span>
                    <span>1-Star</span>
                </div>
                <div class="custom_company_addon_rating_filter_progressbar">
                    <div>
                        <progress value="<?php echo count($five_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($five_star) ?></progress>
                    </div>
                    <div>
                        <progress value="<?php echo count($four_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($four_star) ?></progress>
                    </div>
                    <div>
                        <progress value="<?php echo count($three_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($three_star) ?></progress>
                    </div>
                    <div>
                        <progress value="<?php echo count($two_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($two_star) ?></progress>
                    </div>
                    <div>
                        <progress value="<?php echo count($one_star) ?>"
                                  max="<?php echo $total_number_of_ratings; ?>"><?php echo count($one_star) ?></progress>
                    </div>
                </div>
                <div class="custom_company_addon_rating_filter_percentage">
                    <span><?php echo $percentage_of_five_star; ?>%</span>
                    <span><?php echo $percentage_of_four_star; ?>%</span>
                    <span><?php echo $percentage_of_three_star; ?>%</span>
                    <span><?php echo $percentage_of_two_star; ?>%</span>
                    <span><?php echo $percentage_of_one_star; ?>%</span>
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
        global $wpdb;

        // Ensure the post ID is an integer
        $post_id = intval($post_id);

        // Prepare and execute the query to get comment IDs
        $comment_ids = $wpdb->get_col($wpdb->prepare("
            SELECT comment_ID 
            FROM $wpdb->comments 
            WHERE comment_post_ID = %d 
            AND comment_approved = '1'
        ", $post_id));

        return $comment_ids;
    }

    public function comments_template()
    {
        add_shortcode("custom_company_comments_template", function () {
            $args = array();
            if (is_singular('company') && get_query_var('rating')) {
                $meta_value = get_query_var('rating');

                // Modify the global comments query
                add_filter('comments_clauses', function ($clauses) use ($meta_value) {
                    global $wpdb;
                    $clauses['where'] .= $wpdb->prepare(" AND $wpdb->commentmeta.meta_key = %s AND $wpdb->commentmeta.meta_value = %s", 'your_meta_key', $meta_value);
                    $clauses['join'] .= " INNER JOIN $wpdb->commentmeta ON $wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id ";
                    return $clauses;
                });
                echo "works";
                // Call wp_list_comments
                wp_list_comments($args);

                // Remove the filter after calling wp_list_comments
                remove_all_filters('comments_clauses');
            } else {
                // Call wp_list_comments without filtering
                wp_list_comments($args);
            }
        });
    }
}