<?php

class CustomCompanyAddonCompany
{
    public function __construct()
    {
        add_action("wp_ajax_search_company", [$this, 'get_search_results']);
        add_action("wp_ajax_nopriv_search_company", [$this, 'get_search_results']);

        // shortcode: [company_search_form]
        add_shortcode('company_search_form', [$this, 'search_form_shortcode']);

        add_action('wp', function(){
            // Elementor new company submission manupulate
            add_action('elementor_pro/forms/new_record', [$this, 'create_new_company_from_elementor_submission'], 10, 2);
        });
    }

    /**
     * Ajax call for company search result
     * @return void
     */
    public function get_search_results()
    {
        $query = $_POST['search_query'];
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'search_company')) {
            echo ['error' => "nonce verification failed"];
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
                LIMIT %d
                ",
            'company', 'publish', $search_string, '_thumbnail_id', 15
        );

        // Execute the query
        $results = $wpdb->get_results($query);

        // Process the results to get the thumbnail URLs
        $formatted_results = [];
        foreach ($results as $result) {
            $formatted_result = [];
            $formatted_result['thumbnailUrl'] = wp_get_attachment_image_url($result->thumbnail_id, $thumbnail_size);
            $formatted_result['title'] = $result->post_title;
            $formatted_result['exceprt'] = $result->post_excerpt;
            $formatted_result['permalink'] = get_permalink($result->ID);
            $formatted_results[] = $formatted_result;
        }
        return $formatted_results;
    }

    /**
     * Search form Shortcode
     * @return false|string
     */
    public function search_form_shortcode()
    {
        ob_start();
        ?>
        <div class="company_search_container">
            <div>
                <form action="#" id="company_search_form">
                    <input placeholder="Search Company" type="text">
                </form>
            </div>
            <div style="position: relative">
                <ul id="company_search_result">

                </ul>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    public function create_new_company_from_elementor_submission($record, $handler)
    {
        // Get the form ID from the submitted record
        $form_id = $record->get_form_settings('id');

        // Check if this is the form you want to target
        if ($form_id === 'submit_new_company') {
            // Get submitted fields data
            $fields = $record->get('fields');

            file_put_contents(__DIR__ ."/test.log", print_r($fields, true));

        }

    }
}

