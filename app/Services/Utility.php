<?php

namespace App\Services;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;


class Utility
{
    public static function generateRandomColor(): string
    {
        // Генерируем значения RGB в диапазоне 100-255 для яркости
        $r = mt_rand(100, 255);
        $g = mt_rand(100, 255);
        $b = mt_rand(100, 255);

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    public static function generateInviteCode($uniaqueString)
    {
        $prefix = Str::random(32); // Случайная строка
        $uniqueString = uniqid(microtime(true), true); // Уникальный ID с высокой точностью
        $randomBytes = bin2hex(random_bytes(16)); // Криптографически безопасные случайные байты
        $uuid4 = Uuid::uuid4()->toString();

        return md5($prefix . $uniqueString . $uuid4 . $randomBytes . $uniaqueString);
    }
    public static function generateUniqueString(): string
    {
        $prefix = Str::random(32); // Случайная строка
        $uniqueString = uniqid(microtime(true), true); // Уникальный ID с высокой точностью
        $randomBytes = bin2hex(random_bytes(16)); // Криптографически безопасные случайные байты

        return md5($prefix . $uniqueString . $randomBytes);
    }
    public static function generateUniqueId(): string
    {
        return Uuid::uuid4()->toString(); // Например: "f47ac10b-58cc-4372-a567-0e02b2c3d479"
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
