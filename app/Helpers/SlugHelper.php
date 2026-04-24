<?php

namespace App\Helpers;

class SlugHelper
{
    public static function slugify(string $text): string
    {
        $text = str_replace(['ي', 'ك'], ['ی', 'ک'], $text);
        $text = preg_replace('/[^\p{Arabic}a-zA-Z0-9\s]+/u', '', $text);
        $text = preg_replace('/[\s\-]+/', '-', $text);
        return trim(mb_strtolower($text), '-');
    }
}