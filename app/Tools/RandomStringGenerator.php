<?php


namespace app\Tools;


class RandomStringGenerator
{
    const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public static function generate(int $length): string
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen(self::ALPHABET) - 1);
            $result .= self::ALPHABET[$index];
        }

        return $result;
    }
}