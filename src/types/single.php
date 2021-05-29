<?php

namespace andyp\breadcrumb\types;


class single
{

    private $results = 'post';

    public function __construct()
    {
        $this->generate_html();
    }

    public function get_results()
    {
        return $this->results;
    }

    private function generate_html()
    {
        global $wp_query;
        
        $html = '<a href="/" class="w-80 bg-green-400 px-4 py-2 rounded-2xl">';
        $html .= 'Single';
        $html .= '</a>';

        $this->results = $html;
    }
}