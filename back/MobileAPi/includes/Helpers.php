<?php

class Helpers
{

    static function getPhoneNumerQuery($phoneNumber)
    {
        if (strpos($phoneNumber, '+970') !== false || strpos($phoneNumber, '+972') !== false) {
            $phoneNumberWithoutIntro = substr($phoneNumber, 4);
            return "REGEXP '^[+](970|972)($phoneNumberWithoutIntro)'";
        } else {
            return "='$phoneNumber'";
        }
    }

}
