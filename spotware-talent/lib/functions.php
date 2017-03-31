<?php

function __autoload($class_name)
{
    //class directories
    $directorys = array(
        'lib/',
        'models/',
        'controllers/',
    );

    foreach($directorys as $directory)
    {
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
    
    throw new Exception('No such file: '.$class_name . '.php');
}

