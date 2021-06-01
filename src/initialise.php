<?php

namespace andyp\breadcrumb;


class initialise
{

    use render;

    private $wp;
    private $colour;
    private $result_array;
    private $results;

    public function register()
    {
        add_shortcode( 'breadcrumb', [$this, 'run'] );
    }


    public function run($atts = array())
    {
        $this->colour = $atts['colour'];

        global $wp_query;
        $this->wp = $wp_query;

        $this->enqueue_assets();

        $this->tag('open_flexbox');

        $this->assertain_page_type();

        $this->tag('close_flexbox');

        $this->tag('include_svg');

        $this->concat_results();

        echo $this->results;
    }

    
    private function assertain_page_type()
    {

        $this->page_type('cpt'); 

        // [Homepage] --> [ CPT ] --> [Category] --> [Subcategory]
        if ( is_tax() ) {  
            $this->page_type('tax'); 
        }

        // [Homepage] --> [ CPT ] --> [Category] --> [Subcategory] --> [Single]
        if ( is_single() ) { 
            $this->page_type('post_tax');
        }

    }


    private function page_type($type)
    {
        $ns = 'andyp\\breadcrumb\\types\\'.$type;
        $page_type = new $ns;
        $page_type->set_colour($this->colour);
        $page_type->run();
        $this->result_array[] = $page_type->get_results();
    }


    private function tag($function)
    {
        $this->result_array[] = $this->$function();
    }


    private function concat_results()
    {
        foreach ($this->result_array as $result)
        {
            $this->results .= $result;
        }
    }

    private function enqueue_assets()
    {
        wp_register_style(  'andyp_breadcrumb_css', ANDYP_BREADCRUMB_URL . '/src/css/style.css' ); 
        wp_enqueue_style(   'andyp_breadcrumb_css');
    }
}