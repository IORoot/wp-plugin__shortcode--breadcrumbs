<?php

namespace andyp\breadcrumb\html;


class accordion
{

    private $label;
    private $list;
    private $colour;
    private $hightlight;
    private $noarrow = false;

    private $html;
    private $results;



    public function set_label($label)
    {
        $this->label = $label;
    }

    /**
     * 
     * [
     *      'Title1' => 'URL1',
     *      'Title2' => 'URL2'
     * ]
     * 
     */
    public function set_list(array $list)
    {
        $this->list = $list;
    }

    public function set_colour($colour)
    {
        $this->colour = $colour;
    }

    public function set_highlight($truefalse = false)
    {
        $this->hightlight = 'bg-gray-200 fill-gray-300 hover:bg-'.$this->colour.' hover:text-white hover:fill-white';
        if (!$truefalse) { return; }
        $this->hightlight = 'bg-'.$this->colour.' text-white fill-white hover:underline ';
    }

    public function no_arrow($noarrow)
    {
        $this->noarrow = $noarrow;
    }

    public function build()
    {
        $this->html_label();
        $this->html_accordion_content();
        $this->implode_html();
    }

    public function get_results()
    {
        return $this->results;
    }


    private function html_label()
    {
        $this->html[] .= '<input class="accordion_checkbox absolute opacity-0 z-0" type="checkbox" id="'.$this->label.'"></input>';
        $this->html[] .= '<label class="accordion_label flex '.$this->hightlight.' px-4 py-2 cursor-pointer rounded-2xl text-center z-10 absolute w-40 text-sm" for="'.$this->label.'">';
            $this->html[] .= '<div class="font-light flex-1">' . ucfirst($this->label) . '</div>';
            $this->html[] .= '<svg class="w-4 h-4 m-auto transform rotate-90"><use xlink:href="#chevron-right"></use></svg>';
            if (!$this->noarrow) {
                $this->html[] .= '<div class="absolute top-1 -left-2 text-'.$this->colour.' text-lg">&#9656;</div>';
            }
        $this->html[] .= '</label>';
    }


    private function html_accordion_content() 
    {
        $this->html[] .= '<div class="accordion_content flex-col hidden absolute bg-gray-100 pt-8 top-5 z-0 w-40 rounded-b-2xl overflow-hidden overflow-y-auto max-h-40">';

        foreach ($this->list as $this->item_name => $this->item_url)
        {
            $this->html_accordion_list_item();
        }

        $this->html[] .= '</div>';
    }


    private function html_accordion_list_item()
    {
        $this->html[] .= '<a href="'.strtolower($this->item_url).'" class="flex px-4 py-2 capitalize fill-gray-300 hover:bg-'.$this->colour.' hover:text-white hover:fill-white hover:underline">';
            $this->html[] .= '<div class="flex-1 font-light text-sm">' . $this->item_name . '</div>';
            $this->html[] .= '<svg class=" w-4 h-4 m-auto"><use xlink:href="#chevron-right"></use></svg>';
        $this->html[] .= '</a>';
    }


    private function implode_html()
    {
        $this->results = implode('', $this->html);
    }


}