<?php
defined('_DEFVAR') or exit('Restricted Access');
include_once(__DIR__."/../config.php");
include_once("languages.php");

function trans($str){
    global  $lang;
    global  $languages;
    $result = isset($languages[$lang]) ? $languages[$lang] :  $languages['en'];
    $result = isset($result[$str]) ? $result[$str] : str_replace('_', ' ', $str);
    return $result;
}
function db_trans($row, $field){
    $row = is_array($row) || ! $row ? $row : $row->toArray();
    global  $lang;
    $lang = isset($lang) ? $lang : 'en';
    return isset($row[$field.'_'.$lang]) ? $row[$field.'_'.$lang] : (isset($row[$field.'_en']) ? $row[$field.'_en'] : '');
}
