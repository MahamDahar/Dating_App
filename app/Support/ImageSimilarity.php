<?php

namespace App\Support;

class ImageSimilarity
{
    public static function score(string $firstPath, string $secondPath): float
    {
        $firstHash = self::averageHash($firstPath);
        $secondHash = self::averageHash($secondPath);

        if ($firstHash === null || $secondHash === null) {
            return 0.0;
        }

        $distance = 0;
        $length = strlen($firstHash);

        for ($i = 0; $i < $length; $i++) {
            if ($firstHash[$i] !== $secondHash[$i]) {
                $distance++;
            }
        }

        return round((1 - ($distance / $length)) * 100, 2);
    }

    private static function averageHash(string $path): ?string
    {
        if (
            !\function_exists('imagecreatefromstring')
            || !\function_exists('imagecreatetruecolor')
            || !\function_exists('imagecopyresampled')
            || !\function_exists('imagefilter')
            || !\function_exists('imagecolorat')
            || !\function_exists('imagesx')
            || !\function_exists('imagesy')
            || !\function_exists('imagedestroy')
        ) {
            return null;
        }

        if (!is_file($path)) {
            return null;
        }

        $raw = @file_get_contents($path);
        if ($raw === false) {
            return null;
        }

        $source = @\imagecreatefromstring($raw);
        if ($source === false) {
            return null;
        }

        $size = 8;
        $thumb = \imagecreatetruecolor($size, $size);
        if ($thumb === false) {
            \imagedestroy($source);
            return null;
        }

        \imagecopyresampled($thumb, $source, 0, 0, 0, 0, $size, $size, \imagesx($source), \imagesy($source));
        \imagefilter($thumb, IMG_FILTER_GRAYSCALE);

        $pixels = [];
        $sum = 0.0;

        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $rgb = \imagecolorat($thumb, $x, $y);
                $gray = $rgb & 0xFF;
                $pixels[] = $gray;
                $sum += $gray;
            }
        }

        \imagedestroy($thumb);
        \imagedestroy($source);

        $average = $sum / count($pixels);
        $hash = '';

        foreach ($pixels as $pixel) {
            $hash .= $pixel >= $average ? '1' : '0';
        }

        return $hash;
    }
}
