<?php

namespace App\Services;

class Utility
{
    public static function generateRandomColor(): string
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    public static function hexSumStrings($str1, $str2) {
        // Переводим строки в hex
        $hex1 = bin2hex($str1);
        $hex2 = bin2hex($str2);

        // Дополняем более короткую строку нулями слева
        $len1 = strlen($hex1);
        $len2 = strlen($hex2);
        $maxLen = max($len1, $len2);

        $hex1 = str_pad($hex1, $maxLen, '0', STR_PAD_LEFT);
        $hex2 = str_pad($hex2, $maxLen, '0', STR_PAD_LEFT);

        $result = '';
        $carry = 0;

        // Складываем посимвольно с конца
        for ($i = $maxLen - 1; $i >= 0; $i--) {
            $digit1 = hexdec($hex1[$i]);
            $digit2 = hexdec($hex2[$i]);

            $sum = $digit1 + $digit2 + $carry;
            $carry = (int) ($sum / 16);
            $sum %= 16;

            $result = dechex($sum) . $result;
        }

        if ($carry > 0) {
            $result = dechex($carry) . $result;
        }

        return $result;
    }
}
