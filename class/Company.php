<?php

class CustomCompanyAddonCompany
{
    public function __construct()
    {
        add_action("wp_ajax_search_company", [$this, 'get_search_results']);
        add_action("wp_ajax_nopriv_search_company", [$this, 'get_search_results']);

        // shortcode: [company_search_form]
        add_shortcode('company_search_form', [$this, 'search_form_shortcode']);

        // Elementor new company submission manupulate
        add_action('elementor_pro/forms/new_record', [$this, 'handle_elementor_new_company_from_submission'], 10, 2);
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

    /**
     * Handle Create new company by elementor form submission
     * @param $record
     * @param $handler
     * @return void
     */
    public function handle_elementor_new_company_from_submission($record, $handler)
    {
        // Get the form ID from the submitted record
        $form_id = $record->get_form_settings('id');
        $fields = $record->get('fields');

        // Get submitted fields data
        if($form_id === 'e4e3bff'){ // 'e4e3bff' is form ID of elementor in this case
            $this -> create_new_company([
                'image_url' => $fields['new_company_thumbnail']['value'],
                'excerpt' => $fields['shortdescription']['value'],
                'user_id' => $fields['userid']['value'],
                'content' => $fields['description']['value'],
                'title' => $fields['title']['value'],
                'custom_fields' => [
                    'country' => $fields['country']['value'],
                    'city' => $fields['city']['value'],
                    'zip_code' => $fields['zipcode']['value'],
                    'email' => $fields['email']['value'],
                    'contact_number' => $fields['contactnumber']['value'],
                    'address' => $fields['address']['value'],
                    'website' => $fields['websitelink']['value'],
                    'website_name_as_text' => $fields['websitetext']['value'],
                ]
            ]);
        }

    }

    /**
     * Create new company post
     * @param $post_data
     * @return int|WP_Error
     */
    public function create_new_company($post_data){

        // TODO: Category adding functionality has to be added

        // Create post object
        $post_args = array(
            'post_title'    => wp_strip_all_tags($post_data['title']),
            'post_content'  => $post_data['content'],
            'post_status'   => 'draft',
            'post_author'   => $post_data['user_id'],
            'post_type'     => 'company',
            'post_excerpt'  => $post_data['excerpt'],
        );

        // Insert the post into the database
        $post_id = wp_insert_post($post_args);

        // Add custom fields using ACF
        if ($post_id && !is_wp_error($post_id)) {
            foreach ($post_data['custom_fields'] as $key => $value) {
                update_field($key, $value, $post_id);
            }

            // Upload and set the featured image
            $image_url = $post_data['image_url'];
            $image_id = $this -> upload_image_by_url($image_url);
            if (!is_wp_error($image_id)) {
                set_post_thumbnail($post_id, $image_id);
            }
        }

        return $post_id;
    }


    /**
     * Upload image from URL and set as product thumbnail
     *
     * @param string $image_url
     *
     * @return int|WP_Error Attachment ID on success, WP_Error on failure.
     */
    public function upload_image_by_url(string $image_url)
    {
        // Ensure the URL is valid
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            return new WP_Error('invalid_url', 'Invalid URL provided');
        }

        // Download the image to a temporary location
        $temp_file = download_url($image_url);

        if (is_wp_error($temp_file)) {
            error_log('Download error: ' . $temp_file->get_error_message());

            return new WP_Error('download_error', 'Failed to download image');
        }

        $image_name = basename($image_url);
        // Parse the URL and extract the path
        $path = parse_url($image_url, PHP_URL_PATH);
        // Get the file extension from the path
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $file_array = [
            'name' => $image_name . ".". $extension,
            'tmp_name' => $temp_file,
        ];

        // Check the type of the file. We'll use this as the 'post_mime_type'.
        $file_type = wp_check_filetype($file_array['name'], null);

        if (!getimagesize($file_array["tmp_name"])) {
            // If the file type is not allowed, return an error and delete the temporary file
            unlink($temp_file);

            return new WP_Error('upload_error', 'Sorry, you are not allowed to upload this file type.');
        }

        // Upload the file to the WordPress media library
        $upload = wp_handle_sideload($file_array, ['test_form' => false]);

        if (is_wp_error($upload)) {
            error_log('Upload error: ' . $upload->get_error_message());

            return new WP_Error('upload_error', $upload->get_error_message());
        }

        // Get the file path and URL
        $file_path = $upload['file'];
        $file_url = $upload['url'];

        // Create an attachment post for the image
        $attachment = [
            'guid' => $file_url,
            'post_mime_type' => $file_type['type'],
            'post_title' => sanitize_file_name($image_name),
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        // Insert the attachment post into the database
        $attachment_id = wp_insert_attachment($attachment, $file_path);

        // Include the necessary WordPress files to process the attachment
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        // Generate the attachment metadata and update the database record
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        // Delete the temporary file
        @unlink($temp_file);

        return $attachment_id;
    }
}

