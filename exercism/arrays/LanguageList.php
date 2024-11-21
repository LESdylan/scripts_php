<?php
//create a function that takes variadic parameters
//https://www.php.net/manual/en/functions.arguments.php
//implementation of a variadic function
function language_list(...$args)
{
    return $args;
}

function add_to_language_list(array $array, mixed $language)
{
    $array[] = $language;
    return $array;
}

function prune_language_list(array $arr)
{
    array_shift($arr);
    return $arr;
}

function current_language(array $arr)
{
    return current($arr);
}
function language_list_length(array $arr) : int
{
    return count($arr);
}
$language_list=language_list('c', 'php', 'js');
$pushed_list=add_to_language_list($language_list, 'java');
print_r($pushed_list);
$prune_list = prune_language_list($pushed_list);
print_r($prune_list);
var_dump(current_language($language_list));
echo PHP_EOL.language_list_length($language_list).PHP_EOL;