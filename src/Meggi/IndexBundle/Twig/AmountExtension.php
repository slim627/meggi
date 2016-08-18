<?php
/**
 * Created by PhpStorm.
 * User: archer.developer
 * Date: 03.12.14
 * Time: 16:28
 */

namespace Meggi\IndexBundle\Twig;


class AmountExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('format_amount', array($this, 'format')),
            new \Twig_SimpleFilter('format_agreement', function($number){
                return self::agreementFormat($number);
            }),
            new \Twig_SimpleFilter('format_agreement_static', function($amount){
                return self::staticFormat($amount);
            }),
            new \Twig_SimpleFilter('first_to_upper', function($string){
                return self::firstToUpper($string);
            }),
        );

    }

    public function format($amount)
    {
        $amount = preg_replace('[^0-9]', '', $amount);

        return preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1 ', $amount);
    }

    public static function staticFormat($amount)
    {
        $amount = preg_replace('[^0-9]', '', $amount);

        return preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1 ', $amount);
    }

    public static function firstToUpper($string) {
        $explode = explode(' ', $string);
        $first[] = mb_convert_case($explode[0], MB_CASE_TITLE);
        $array_slice = array_slice($explode, 1, count($explode));

        $array_merge = array_merge($first, $array_slice);
        $implode = implode(' ', $array_merge);

        return mb_convert_encoding($implode, 'utf-8');
    }

    public static  function agreementFormat($number)
    {
        if($number < 10){
            return '000'.$number;
        }
        elseif($number < 100){
            return '00'.$number;
        }
        elseif($number < 1000){
            return '0'.$number;
        }
        else{
            return $number;
        }
    }

    public function getName()
    {
        return 'amount_extension';
    }
}
