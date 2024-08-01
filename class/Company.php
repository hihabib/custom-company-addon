<?php

class CustomCompanyAddonCompany
{
    public function __construct()
    {
        add_action("wp_ajax_search_company", [$this, 'get_search_results']);
        add_action("wp_ajax_nopriv_search_company", [$this, 'get_search_results']);

        // shortcode: [company_search_form]
        add_shortcode('company_search_form', [$this, 'search_form_shortcode']);
    }


    /**
     * Ajax call for company search result
     * @return void
     */
    public function get_search_results()
    {
        $query = $_POST['search_query'];
        $nonce = $_POST['nonce'];
        if(!wp_verify_nonce($nonce, 'search_company')){
            echo ['error'=>"nonce verification failed"];
        }
        $results = self::search_company($query);
        echo json_encode($results);
        die();
    }

    /**
     * Search company via SQL query
     * @param string $search_q
     * @return array|object|stdClass[]|null
     */
    public static function search_company($search_q)
    {
        global $wpdb;

        // Define the meta key for the thumbnail size
        $thumbnail_size = 'thumbnail';
        $search_string = "%" . $search_q . "%";

        // Create the SQL query
        $query = $wpdb->prepare(
            "
                SELECT p.ID, p.post_title, p.post_excerpt, pm.meta_value as thumbnail_id
                FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = %s
                AND p.post_status = %s
                AND p.post_title LIKE %s
                AND pm.meta_key = %s
                ",
            'company', 'publish', $search_string, '_thumbnail_id'
        );

        // Execute the query
        $results = $wpdb->get_results($query);

        // Process the results to get the thumbnail URLs
//        foreach ($results as $result) {
//            // Get the URL of the thumbnail
//            $thumbnail_url = wp_get_attachment_image_url($result->thumbnail_id, $thumbnail_size);
//
//         Get the permalink of the post
//        $permalink = get_permalink($result->ID);
//            // Print or use the data as needed
//            echo 'Title: ' . $result->post_title . '<br>';
//            echo 'Excerpt: ' . $result->post_excerpt . '<br>';
//            echo 'Thumbnail URL: ' . $thumbnail_url . '<br><br>';
//        }
        return $results;
    }

    public function search_form_shortcode(){
        ob_start();
        ?>
        <div class="company_search_container">
            <div>
                <form action="#" id="company_search_form">
                    <input type="text">
                </form>
            </div>
            <div>
                <ul>

                </ul>
            </div>
        </div>

    <?php
        return ob_get_clean();
    }
}

