<?php

if (!function_exists('selectBoxSelectedItems')) {
    function selectBoxSelectedItems($inputDataArray = [])
    {
        return (in_array('all', $inputDataArray) || in_array('999999', $inputDataArray))
                ? ['all', '999999']
                : $inputDataArray;
    }
}

if (!function_exists('formatNumberAsPrice')) {
    function formatNumberAsPrice($number = 0)
    {
        return number_format($number, 2);
    }
}
