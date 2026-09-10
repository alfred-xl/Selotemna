<?php

namespace App\Support;

final class NairaAmountInWords
{
    private const ONES = [
        0 => '',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
    ];

    private const TENS = [
        2 => 'twenty',
        3 => 'thirty',
        4 => 'forty',
        5 => 'fifty',
        6 => 'sixty',
        7 => 'seventy',
        8 => 'eighty',
        9 => 'ninety',
    ];

    private const SCALES = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];

    public static function convert(int $amount): string
    {
        if ($amount === 0) {
            return 'Zero naira only';
        }

        $groups = [];
        $scale = 0;

        while ($amount > 0) {
            $group = $amount % 1000;

            if ($group > 0) {
                $words = self::convertHundreds($group);
                $scaleName = self::SCALES[$scale] ?? '';
                $groups[] = trim($words.' '.$scaleName);
            }

            $amount = intdiv($amount, 1000);
            $scale++;
        }

        return ucfirst(implode(' ', array_reverse($groups))).' naira only';
    }

    private static function convertHundreds(int $number): string
    {
        $parts = [];

        if ($number >= 100) {
            $parts[] = self::ONES[intdiv($number, 100)].' hundred';
            $number %= 100;
        }

        if ($number >= 20) {
            $parts[] = self::TENS[intdiv($number, 10)];
            $number %= 10;
        }

        if ($number > 0) {
            $parts[] = self::ONES[$number];
        }

        return implode(' ', $parts);
    }
}
