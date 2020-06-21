<?php


namespace Core\Essetials;


trait System {

    public static function redirect($url, $with = [], $time = 0, $message = false, $die = false) {
        if (count($with) > 0)
            foreach ($with as $key => $value)
                Session::set($key, $value);
        echo "<meta http-equiv=\"refresh\" content=\"{$time}; URL='{$url}\"/>";
        if ($message)
            echo "<span class='alert alert-danger'>{$message}</span>";
        if ($die)
            die;
    }



}