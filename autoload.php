<?php
/**
 * @ignore 自动加载
 */
if ( file_exists(dirname(__FILE__).'/vendor/autoload.php') ) {
    require_once dirname(__FILE__).'/vendor/autoload.php';
}

function class_loader($className)
{
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace("Easemob" . DIRECTORY_SEPARATOR, "", str_replace("\\", DIRECTORY_SEPARATOR, $className)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('class_loader');

require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions.php';