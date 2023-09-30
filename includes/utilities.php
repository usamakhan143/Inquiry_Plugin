<?php

function dd($v){

    $output = $v;
    if(is_array($output)){
        $objs_Array = [];
        foreach([$output] as $out){
            $object = (object) $out; // Convert the array to an object
            $objs_Array[] = $object;
            $output = $objs_Array;
        }
    }

    

    echo '<blockquote>';
    echo '<pre>';
    var_dump($output);
    echo '</pre>';
    echo '</blockquote>';
}


function get_plugin_options($name) {
    return carbon_get_theme_option($name);
}