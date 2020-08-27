<?php

class Protect
{

    function secureStr($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }
}