<?php
    function error($error = "Not specified") {
        $msg = "Error: {$error}";
        if (DEBUG)
            echo "<span class='error center-by-left'>{$msg}</span>";
        return $msg;
    }

    function pre($var) {
        echo "<pre>";
        var_dump($var);
        echo "<pre>";
    }