<?php

define('PUBLIC_PATH', APPLICATION_PATH . '/../public');

/**
 * some important tags in the head tag
 */
function getHeaders(&$view, $controller = null, $action = null, $module = null) {
    $fileName = APPLICATION_PATH . '/configs/layout.ini';
    if(isset($module) && $module == 'admin'){
        $fileName = APPLICATION_PATH . '/configs/admin/layout.ini';
    }
    $config   = new Zend_Config_Ini($fileName, "layout");

    //set title tag
    if(isset($config->title)) echo '<title>'.$config->title.'</title>';
    //set meta tags
    if(count($config->metaHttpequiv)){
        foreach ($config->metaHttpequiv as $file) {
            $parts = explode('|', $file);
            $counter = count($parts);
            //set http-equiv tags
            if($counter == 2){
                echo '<meta http-equiv="'.$parts[0].'" content="'.$parts[1].'">';
            }elseif($counter == 3){//set other tags
                echo '<meta '.$parts[0].'="'.$parts[1].'" content="'.$parts[2].'">';
            }
        }
    }
}
/**
 * some important tags in the head tag
 */
function getScriptContents(&$view, $controller = null, $action = null, $module = null) {
    $actionScriptFile = "/scripts/controllers/{$controller}/{$action}.js";
    $fileName = APPLICATION_PATH . '/configs/layout.ini';

    if(isset($module) && $module == 'admin'){
        $actionScriptFile = "/scripts/controllers/admin/{$controller}/{$action}.js";
        $fileName = APPLICATION_PATH . '/configs/admin/layout.ini';
    }
    $config = new Zend_Config_Ini($fileName, "layout");

    if (isset($controller) && isset($action)) {
        if(count($config->stableFileJs)){
            foreach ($config->stableFileJs as $file) {
                if (is_file(PUBLIC_PATH . $file)) {
                    echo '<script src="'.$file.'" type="text/javascript"></script>';
                }
            }
        }
        if (is_file(PUBLIC_PATH . $actionScriptFile)) {
            echo '<script src="'.$actionScriptFile.'" type="text/javascript"></script>';
        }
    }
}
/**
 * some important tags in the head tag
 */
function getStyleContents(&$view, $controller = null, $action = null, $module = null) {
    if(isset($module) && $module == 'admin'){
        $fileName = APPLICATION_PATH . '/configs/admin/layout.ini';
        $config = new Zend_Config_Ini($fileName, "layout");

        if(count($config->stableFileCss)){
            foreach ($config->stableFileCss as $file) {
                if (is_file(PUBLIC_PATH . $file)) {
                    echo '<link href="'.$file.'" media="screen" rel="stylesheet" type="text/css">';
                }
            }
        }
    }else{
        $actionScriptFile = "/styles/controllers/{$controller}/{$action}.css";
        $fileName = APPLICATION_PATH . '/configs/layout.ini';
        $config = new Zend_Config_Ini($fileName, "layout");

        if (isset($controller) && isset($action)) {
            if(count($config->stableFileCss)){
                foreach ($config->stableFileCss as $file) {
                    if (is_file(PUBLIC_PATH . $file)) {
                        echo '<link href="'.$file.'" media="screen" rel="stylesheet" type="text/css">';
                    }
                }
            }
            if (is_file(PUBLIC_PATH . $actionScriptFile)) {
                echo '<link href="'.$actionScriptFile.'" media="screen" rel="stylesheet" type="text/css">';
            }
        }
    }
}

function getContentFile($file, $fileName = 'file') {
    $glue = "
\n/*================================================================================
/* $fileName
/*================================================================================*/\n";
    return $glue . file_get_contents($file);
}
