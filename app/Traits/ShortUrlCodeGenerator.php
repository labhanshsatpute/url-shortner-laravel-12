<?php

trait ShortUrlCodeGenerator
{
    public function generateShortUrlCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $short_url_code = '';
        for ($i = 0; $i < $length; $i++) {
            $short_url_code .= $characters[rand(0, $characters_length - 1)];
        }
        return $short_url_code;
    }
}