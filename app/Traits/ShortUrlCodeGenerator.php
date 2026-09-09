<?php

namespace App\Traits;

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

        $this->short_url_code = $short_url_code;
        $this->save();
    }
}