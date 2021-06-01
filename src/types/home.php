<?php

namespace andyp\breadcrumb\types;


class home
{

    private $results = 'home';

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
        $html = '<a href="/" class="breadcrumb_home bg-gray-200 px-4 py-2 font-light rounded-2xl hover:bg-'.$this->colour.' hover:text-white text-center w-20 mr-10 text-sm hover:underline relative z-50">';
        $html .= 'Home';
        $html .= '</a>';

        $this->results = $html;
    }


}