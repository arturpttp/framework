<?php


namespace Core;


class Html
{

    public static function links($paths = [])
    {
        foreach ($paths as $path)
            echo "<link rel='stylesheet' href='{$path}'>";
    }

    public static function input($type = "text", $options = [])
    {
        $options = self::options($options);
        $input = "<input type='{$type}'{$options}>";
        return $input;
    }

    public static function form($content = [], $options = [], $method = "post", $action = false)
    {
        $action = resumeIf($action, $action, "");
        $options = self::options($options);
        $contentString = "";
        $x = 1;
        foreach ($content as $item) {
            $contentString = "";
            if (count($content) )
            $x++;
        }
        $form = "<form {$action} method='{$method}'{$options}>{$contentString}</form>";
        return $form;
    }

    public static function error($_error, $title = false)
    {
        self::links([
            'css/style.css',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'
        ]);
        $error = "<div class='alert alert-danger'>";
        if ($title)
            $error .= "<h4>{$title}</h4><br>";
        $error .= "{$_error}";
        $error .= "</div>";
        return $error;
    }


    private static function options($options)
    {
        $optionsString = "";
        if (count($options) > 0)
            foreach ($options as $key => $value)
                $options = " {$key} = '{$value}'";
        return $optionsString;
    }

}
?>

