<?php


namespace Core\Config;


use Core\Interfaces\ArrayableSystem;

class Config
{

    public $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

//    public static function create($filename, $items = [], $directory = __DIR__ . "/../../app/Configs/", $extension = GENERAL_EXTENSION) {
//        $filename = strpos($filename, ".{$extension}") ? $filename : $filename . ".{$extension}";
//        $file = $directory ."{$filename}";
//        $f = fopen($file, "w");
//        $str = "";
//        $x = 1;
//        pre($items);
//        if (count($items) > 0)
//            foreach ($items as $item) {
//                pre($item);
//                $str .= isset($items[$item]) ? "'{$item}' => '{$items[$item]}'" : "'{$item}'";
//                if ($x < count($items))
//                    $str .= ",\n";
//                $x++;
//                echo $str;
//            }
//        fwrite($f, "<?php\nreturn new \Core\Config\Config([{$str}]);");
//        return require_once $file;
//    }

    public static function get($item, $extension = GENERAL_EXTENSION)
    {
        $items = explode(".", $item);
        if (count($items) < 1)
            return error("Key field is required");
        $config_file_name = array_shift($items) . ".{$extension}";
        $file = __DIR__ . "/../../app/Configs/{$config_file_name}";
        if (!file_exists($file))
            return error("Config file not found {$config_file_name}");
        $config = require_once $file;
        if ($config instanceof Config)
            $config = $config->items;

        foreach ($items as $value) {
            if (!isset($config[$value]))
                return error("{$value} is not a valid key");
            return $config[$value];
        }
    }

}