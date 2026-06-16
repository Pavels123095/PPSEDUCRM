<?php

namespace App\Services;

class SnilsValidator
{
    public function normalize(string $snils): string
    {
        return preg_replace('/\D/', '', $snils) ?? '';
    }

    public function format(string $snils): string
    {
        $digits = $this->normalize($snils);

        if (strlen($digits) !== 11) {
            return $snils;
        }

        return sprintf(
            '%s-%s-%s %s',
            substr($digits, 0, 3),
            substr($digits, 3, 3),
            substr($digits, 6, 3),
            substr($digits, 9, 2)
        );
    }

    public function isValid(?string $snils): bool
    {
        if ($snils === null || $snils === '') {
            return true;
        }

        $digits = $this->normalize($snils);

        if (! preg_match('/^\d{11}$/', $digits)) {
            return false;
        }

        $number = substr($digits, 0, 9);
        $checksum = (int) substr($digits, 9, 2);

        if ((int) $number <= 1001998) {
            return true;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $number[$i] * (9 - $i);
        }

        if ($sum < 100) {
            $expected = $sum;
        } elseif ($sum === 100 || $sum === 101) {
            $expected = 0;
        } else {
            $expected = $sum % 101;
            if ($expected === 100) {
                $expected = 0;
            }
        }

        return $checksum === $expected;
    }
}
