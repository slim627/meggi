<?php

namespace Meggi\IndexBundle\Twig;


class DateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('formatDate', function($date)
            {
                return self::formatDateFilter($date);
            }),
            new \Twig_SimpleFilter('formatDateShort', function($date)
            {
                return self::formatDateShortFilter($date);
            }),
            new \Twig_SimpleFilter('formatTime', function($date)
            {
                return self::formatTimeFilter($date);
            }),
            new \Twig_SimpleFilter('formatDateTime', function($date)
            {
                return self::formatDateFilter($date) . ' ' . self::formatTimeFilter($date);
            }),
            new \Twig_SimpleFilter('timeRemaining', function($date, $delta)
            {
                return self::timeRemainingFilter($date, $delta);
            }),
        );
    }

    public static function formatDateFilter($date)
    {
        if(!$date){
            return '';
        }

        $date_int = strtotime($date->format('Y-m-d H:i:s'));
        $date=explode(".", date("d.m.Y",$date_int));
        switch ($date[1]){
            case 1: $m='января'; break;
            case 2: $m='февраля'; break;
            case 3: $m='марта'; break;
            case 4: $m='апреля'; break;
            case 5: $m='мая'; break;
            case 6: $m='июня'; break;
            case 7: $m='июля'; break;
            case 8: $m='августа'; break;
            case 9: $m='сентября'; break;
            case 10: $m='октября'; break;
            case 11: $m='ноября'; break;
            case 12: $m='декабря'; break;
        }

        $date[1] = $m;
        $date =  implode(' ', $date);
        return $date;
    }

    public static function formatTimeFilter($date)
    {
        return $date->format('H:i');
    }

    public static function formatDateShortFilter($date)
    {
        return $date->format('d.m.Y');
    }

    public static function timeRemainingFilter($date, $delta)
    {
        $now = new \DateTime();
        $temp = clone($date);
        $temp = $temp->modify('+'.$delta.' hours');

        $diff = $now->diff($temp);
        if($diff->invert){
            return false;
        }

        return ($diff->days) ? $diff->format('%d день %h ч') : $diff->format('%h ч %i мин');
    }

    public function getName()
    {
        return 'date_extension';
    }
}