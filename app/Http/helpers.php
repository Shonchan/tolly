<?php

function mb_lcfirst($string, $enc='UTF-8')
{
        $result =  mb_strtolower(mb_substr($string, 0, 1, $enc), $enc) .  mb_substr($string, 1, mb_strlen($string, $enc), $enc);

        return $result;
}

function plural($number, $arr)
{
    $singular = $arr[0];
    $plural1 = $arr[1];
    if ($arr[2]) $plural2 = $arr[2]; else $plural2 = null;
    $number = abs($number);
    if(!empty($plural2))
    {
        $p1 = $number%10;
        $p2 = $number%100;
        if($number == 0)
            return $plural1;
        if($p1==1 && !($p2>=11 && $p2<=19))
            return $singular;
        elseif($p1>=2 && $p1<=4 && !($p2>=11 && $p2<=19))
            return $plural2;
        else
            return $plural1;
    }else
    {
        if($number == 1)
            return $singular;
        else
            return $plural1;
    }

}