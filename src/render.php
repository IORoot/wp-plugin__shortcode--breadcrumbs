<?php

namespace andyp\breadcrumb;


trait render
{

    public function open_flexbox()
    {
        return '<div class="flex flex-col md:flex-row items-start breadcrumbs">';
    }

    public function close_flexbox()
    {
        return '</div>';
    }

    public function include_svg()
    {
        $svg  = file_get_contents(ANDYP_BREADCRUMB_PATH.'/src/svg/open-external.svg');
        $svg .= file_get_contents(ANDYP_BREADCRUMB_PATH.'/src/svg/chevron-right.svg');
        return $svg;
    }

}