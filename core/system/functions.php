<?php
    function error($error = "Not specified") {
        $msg = "Error: {$error}";
        echo "<span class='error center-by-left'>{$msg}</span>";
        return $msg;
    }