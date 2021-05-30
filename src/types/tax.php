<?php

namespace andyp\breadcrumb\types;

use andyp\breadcrumb\html\accordion;

class tax
{

    private $wp;

    private $current_taxonomy;
    private $top_category;
    private $top_category_list;
    private $top_category_highlight;
    private $sub_category;
    private $sub_category_list;
    private $sub_category_highlight;
    private $post_title;
    private $post_list;

    private $html;
    private $results = 'tax';

    public function __construct()
    {
        $this->set_variables();
        $this->generate_top_category();
        $this->generate_top_category_list();
        $this->generate_sub_category();
        $this->generate_sub_category_list();
        $this->generate_post_title();
        $this->generate_post_list();
        $this->generate();
        $this->implode_html();
    }

    public function get_results()
    {
        return $this->results;
    }

    private function set_variables()
    {
        global $wp_query;
        $this->wp = $wp_query;
        $this->current_taxonomy = $this->wp->queried_object->taxonomy;

    }

    private function generate_top_category()
    {
        if( $this->wp->queried_object->parent == 0 ) { 
            $this->top_category = $this->wp->queried_object->name;
            $this->top_category_highlight = true;
            return;
        }   
        $this->top_category = get_term( $this->wp->queried_object->parent )->name;
    }


    private function generate_sub_category()
    {
        if( $this->wp->queried_object->parent == 0 ) { 
            $this->sub_category = "All Series";
            $this->wp->queried_object->parent = $this->wp->queried_object->term_id;
            return;
        }   
        $this->sub_category = $this->wp->queried_object->name;
        $this->sub_category_highlight = true;
    }

    private function generate_post_title()
    {
        $this->post_title = 'All Videos';
    }


    private function generate_top_category_list()
    {
        $top_list = get_terms( [ 'taxonomy' => $this->current_taxonomy, 'parent' => 0, 'hide_empty' => false ] );
        foreach ($top_list as $loop_taxonomy)
        {
            $this->top_category_list[$loop_taxonomy->name] = get_term_link($loop_taxonomy);
        }
    }



    private function generate_sub_category_list()
    {
        if (!$this->sub_category){ return; }

        $sub_list = get_terms( [ 'taxonomy' => $this->current_taxonomy, 'parent' => $this->wp->queried_object->parent, 'hide_empty' => false ]);
        foreach ($sub_list as $loop_taxonomy)
        {
            $this->sub_category_list[$loop_taxonomy->name] = get_term_link($loop_taxonomy);
        }
    }



    private function generate_post_list()
    {
        if (!$this->sub_category){ return; }

        foreach ($this->wp->posts as $post)
        {
            $this->post_list[$post->post_title] = get_permalink($post);
        }
    }



    private function generate()
    {

        $this->generate_accordion($this->top_category, $this->top_category_list, 'top', $this->top_category_highlight);
    
        if(!$this->sub_category ){ return; }
        
        $this->generate_accordion($this->sub_category, $this->sub_category_list, 'sub', $this->sub_category_highlight);

        $this->generate_accordion($this->post_title, $this->post_list, 'post', false);

    }



    private function generate_accordion($label, $list, $level, $highlight)
    {
        $this->html[] .= '<div class="breadcrumb_'.$level.' cpt_tab relative w-40 mr-10 z-50">';

        $accordion = new accordion;
        $accordion->set_label($label);
        $accordion->set_list($list);
        $accordion->set_highlight($highlight);
        $accordion->build();
        $this->html[] .= $accordion->get_results();
        
        $this->html[] .= '</div>';
    }



    private function implode_html()
    {
        $this->results = implode('', $this->html);
    }

}