<?php
declare(strict_types=1);
//reverse sequence of DNA or RNA, is important for various analysis, such as finding complementary strands or identifying palindromic sequences that have biological significance.

function reverseString(string $str): string
{
    $length = strlen($str);
    $reversed = ''; // Initialize an empty string
    $i = $length;   // Start index for traversing

    while ($i-- > 0)
        $reversed .= $str[$i]; // Append characters in reverse order

    return $reversed; // Return the reversed string
}


$str = "stressed";
$str1 = "strops";
$str2= "racecar";
//echo strrev($str).PHP_EOL;
//echo strrev($str1).PHP_EOL;
//echo strrev($str2).PHP_EOL;
echo reverseString($str);