<?php


namespace Core\Entities;


use Core\System\Arr;

class Assets
{

    public $css;
    public $js;
    public $images;

    public function load($echo = true)
    {

        $css = [];
        $js = [];
        $images = [];

        foreach ($css as $item)
            $css[] = "<link rel='stylesheet' href='{$item}'>";
        foreach ($js as $item)
            $js[] = "<script src='{$item}'></script>";
        foreach ($images as $item)
            $images[] = "<img src='{$item}' alt=''>";
        $assets = Arr::merge($css, $js, $images);
        foreach ($assets as $asset)
            if ($echo)
                echo $asset;
    }

}
