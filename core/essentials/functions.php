<?php
function error($error = "Not specified")
{
    $msg = "Error: {$error}";
    if (DEBUG)
        echo "<span class='error center-by-left'>{$msg}</span>";
    return $msg;
}

function pre($var)
{
    echo "<pre>";
    var_dump($var);
    echo "<pre>";
}

function resumeIf($condition, $true, $false)
{
    return $condition ? $true : $false;
}

function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}

function errorHandler($number, $message, $file, $line) {
    echo "
        <div class='error-handler'>
            <h4><b>An error has occurred</b></h4><br>
            <span>Error code <b>#{$number}</b></span><br>
            <span>Message <b>{$message}</b></span><br>
            <span>File <b>{$file}:{$line}</b></span><br>
            <span>Line <b>{$line}</b></span>
        </div>
    ";
}
set_error_handler('errorHandler', E_ALL);


