<?php

namespace App\Traits;

trait RandomStringGenerator
{   
    private $char = 'abcdefghijklmnopqrstuvwxyz0123456789';

    function generateString($length = 8) 
    {   
        $randomString = '';

        for ($i = 0; $i < $length; $i++)
        {
            $randomChar = $this->char[rand(0, strlen($this->char) - 1)];
            
            if (ctype_alpha($randomChar) && rand(0, 1) === 1)
            {
                $randomChar = strtoupper($randomChar);
            }

            $randomString .= $randomChar;
        }

        return $randomString;
    }
}
