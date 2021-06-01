<?php

namespace andyp\breadcrumb\types;

use andyp\breadcrumb\html\accordion;

class cpt
{

    private $wp;
    private $CPT;

    private $colour;

    private $current_CPT;

    private $html = [];
    private $results = 'cpt';

    public function run()
    {
        $this->variables();
        $this->current_cpt();
        $this->generate_html();
        $this->implode_html();
    }

    public function get_results()
    {
        return $this->results;
    }

    public function set_colour($colour)
    {
        $this->colour = $colour;
    }


    private function variables()
    {
        global $wp_query;
        $this->wp = $wp_query;
        $this->CPT = get_post_types( [ 'public' => true, '_builtin' => false ] , null, 'and' );        
    }

    private function current_cpt()
    {
        $this->current_CPT = $this->wp->post->post_type;
    }

    private function generate_html()
    {
        $this->html[] .= '<div class="breadcrumb_cpt cpt_tab relative w-40 mr-10 z-50">';

        foreach ($this->CPT as $loop_cpt)
        {
            // 'Title' => 'URL'
            $list[$loop_cpt->label] = '/'.$loop_cpt->label;
        }

        $accordion = new accordion;
        $accordion->set_label($this->current_CPT);
        $accordion->set_list($list);
        $accordion->set_colour($this->colour);
        $accordion->set_highlight();
        $accordion->no_arrow(true);
        $accordion->build();

        $this->html[] .= $accordion->get_results();

        $this->html[] .= '</div>';
    }


    private function implode_html()
    {
        $this->results = implode('', $this->html);
    }


}