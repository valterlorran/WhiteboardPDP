<?php

spl_autoload_register(function($class) {
    $namespace = explode('\\', $class, 2);
    if ($namespace[0] === 'WhiteboardPDP' && isset($namespace[1])) {
        include(__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'WhiteboardPDP' . DIRECTORY_SEPARATOR . $namespace[1] . '.php');
    }
});
