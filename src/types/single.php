<?php

namespace andyp\breadcrumb\types;


class single
{

    private $results = 'post';

    private $colour;

    public function run()
    {
        $this->generate_html();
    }

    public function set_colour($colour)
    {
        $this->colour = $colour;
    }

    public function get_results()
    {
        return $this->results;
    }

    private function generate_html()
    {
        global $wp_query;
        
        $html = '<a href="/" class="w-80 bg-green-400 px-4 py-2 rounded-2xl z-50">';
        $html .= 'Single';
        $html .= '</a>';

        $this->results = $html;
    }
}