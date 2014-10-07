<?php
namespace {
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    function __autoload($class) {
        $class = str_replace('\\', '/', $class);
        $class = preg_replace('/^Application/', 'application', $class);
        require_once $class.'.php';
    }

    set_include_path(
        realpath(dirname(__FILE__).'/../').PATH_SEPARATOR.
        realpath(dirname(__FILE__).'/../library')
    );
}

namespace Application {
    const PROJECT_PATH = '../';

    $bootstrap = new Bootstrap(new \Nick\PlayBowl());
    $bootstrap->run();
}