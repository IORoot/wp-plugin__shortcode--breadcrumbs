<?php

namespace andyp\breadcrumb\types;

use andyp\breadcrumb\html\accordion;

class post_tax
{

    private $wp;

    private $colour;
    private $taxonomy_object;

    private $post_title;
    private $post_list;

    private $sub_category;
    private $sub_category_list;

    private $top_category;
    private $top_category_list;

    private $html;
    private $results = 'tax';

    public function run()
    {
        $this->set_variables();
        $this->get_terms_hierarchical(); 

        $this->generate_post_title();           // work backwards post->sub->top

        $this->generate_top_category_list();
        
        $this->generate_sub_category_list();
        
        $this->generate_post_list();    

        $this->generate();
        $this->implode_html();
    }

    public function set_colour($colour)
    {
        $this->colour = $colour;
    }

    public function get_results()
    {
        return $this->results;
    }

    private function set_variables()
    {
        global $wp_query;
        $this->wp = $wp_query;
    }



    private function get_terms_hierarchical() {    

        $taxonomies_of_post = get_object_taxonomies( $this->wp->queried_object );

        // tutorial_cateogry / tutorial_tags
        foreach ( $taxonomies_of_post as $taxonomy ) {  

            $taxonomy_object = get_taxonomy( $taxonomy );  

            // check hierarchical
            if ( !$taxonomy_object->query_var || !$taxonomy_object->hierarchical ) { return; }

            $this->taxonomy_object = $taxonomy_object;
    
            $sub_terms = wp_get_post_terms( $this->wp->queried_object->ID, $this->taxonomy_object->name, array( 'orderby' => 'term_id' ) );

            foreach ( $sub_terms as $loop_sub_term ) {

                // If there is a single category
                if ($loop_sub_term->parent == 0){
                    $this->top_category[$loop_sub_term->term_id] = $loop_sub_term->name;
                    continue;
                } else {
                    $this->sub_category[$loop_sub_term->term_id] = $loop_sub_term->name;
                }
                
                // If there is a parent category, go to next loop
                // if ($loop_sub_term->parent == 0){ continue; }

                $parent = get_term($loop_sub_term->parent);
                $this->top_category[$parent->term_id] = $parent->name;
            }
            
        }
    }



    private function generate_post_title()
    {
        $this->post_title = $this->wp->queried_object->post_title;
    }





    private function generate_top_category_list()
    {
        $top_list = get_terms( [ 'taxonomy' => $this->taxonomy_object->name, 'parent' => 0, 'hide_empty' => false ] );
        foreach ($top_list as $loop_taxonomy)
        {
            $this->top_category_list[$loop_taxonomy->name] = get_term_link($loop_taxonomy);
        }
    }



    private function generate_sub_category_list()
    {
        if (!$this->sub_category){ return; }

        $sub_list = get_terms( [ 'taxonomy' => $this->taxonomy_object->name, 'parent' => array_key_first($this->top_category), 'hide_empty' => false ]);
        foreach ($sub_list as $loop_taxonomy)
        {
            $this->sub_category_list[$loop_taxonomy->name] = get_term_link($loop_taxonomy);
        }
    }



    private function generate_post_list()
    {

        if ( $this->sub_category){ $key = $this->sub_category; }
        if (!$this->sub_category){ $key = $this->top_category; }

        $posts = get_posts([
            'post_type'   => $this->wp->queried_object->post_type,
            'numberposts' => -1,
            'tax_query' => [
                array(
                    'taxonomy' => $this->taxonomy_object->name,
                    'field' => 'term_id', 
                    'terms' => array_key_first($key),
                    'include_children' => false
                )
            ]
        ]);

        foreach ($posts as $post)
        {
            $this->post_list[$post->post_title] = get_permalink($post);
        }

        $this->post_list = array_reverse($this->post_list);
    }



    private function generate()
    {

        if ($this->top_category_list) {
            $this->generate_accordion(array_shift($this->top_category), $this->top_category_list, 'top', false, '40');
        }
        
        if ($this->sub_category_list) {
            $this->generate_accordion(array_shift($this->sub_category), $this->sub_category_list, 'sub', false, '30');
        }

        if ($this->post_list) {

            $label = $this->post_title;
            if (strpos($label, ' - ') !== false) {
                $label = explode(" - ", $label);
                $label = $label[1];
            }
            
            $this->generate_accordion($label, $this->post_list, 'post', true, '20');
        }
    }



    private function generate_accordion($label, $list, $level, $highlight, $zindex)
    {
        $this->html[] .= '<div class="breadcrumb_'.$level.' cpt_tab relative w-full md:w-40 mr-10 z-'.$zindex.' h-9 mb-1 md:mb-0">';

        $accordion = new accordion;
        $accordion->set_label($label);
        $accordion->set_list($list);
        $accordion->set_colour($this->colour);
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