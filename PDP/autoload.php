<?php

spl_autoload_register(function($class) {
    $namespace = explode('\\', $class, 2);
    if ($namespace[0] === 'PDP' && isset($namespace[1])) {
        include(__DIR__ . DIRECTORY_SEPARATOR . $namespace[1] . '.php');
    }
});
