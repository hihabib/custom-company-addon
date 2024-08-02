<?php

class CustomCompanyAddonComment {
    public function  __construct()
    {
        add_filter('query_vars', [$this, 'custom_register_query_vars']);
    }
    public function custom_register_query_vars($vars) {
        $vars[] = 'rating';
        return $vars;
    }

}