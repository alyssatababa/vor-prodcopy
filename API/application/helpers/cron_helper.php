<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function add_date($date_str, $months)
{
    $date = new DateTime($date_str);


    // We extract the day of the month as $start_day
    $start_day = $date->format('j');


    // We add 1 month to the given date
    $date->modify("+{$months} month");


    // We extract the day of the month again so we can compare
    $end_day = $date->format('j');


    if ($start_day != $end_day)
    {
        // The day of the month isn't the same anymore, so we correct the date
        $date->modify('last day of last month');
    }


    return $date;
}


function subtract_date($date_str, $months)
{
    $date = new DateTime($date_str);


    // We extract the day of the month as $start_day
    $start_day = $date->format('j');


    // We add 1 month to the given date
    $date->modify("-{$months} month");


    // We extract the day of the month again so we can compare
    $end_day = $date->format('j');


    if ($start_day != $end_day)
    {
        // The day of the month isn't the same anymore, so we correct the date
        $date->modify('last day of last month');
    }


    return $date;
}