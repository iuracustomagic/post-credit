<?php
namespace App\Classes;
class ReplacePhone
{
    /**
     * @var string
     */
    var $host;
    /**
     * @var string
     */
    var $auth;

    var $timeout;


    function __construct()
    {

    }

    /**

     * @param $text string

     */
    function replace( $text)
    {
        $userPhone =  str_replace(['+', '(', ')', '-', ' '], '', $text);

        if(mb_substr($userPhone, 0, 1) == 8) {
            $userPhone = preg_replace('/8/', '7', $userPhone, 1);
        };
        return $userPhone;
    }


}
