<?php

function __autoload($class_name)
{
    $path = "{$class_name}.php";

    if (file_exists($path))
        require_once($path);
    else
        die("The file {$class_name}.php could not be found.\n");
}
