<?php


namespace app\models;


class Calculate
{
    /**
     * Random value
     * @param $min
     * @param $max
     * @return double random value
     */
    public static function randomFloat($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }
    /**
     * Calculate distance
     * @param $p first point
     * @param $q second point
     * @return double distance
     */
    public static function LatLngDist($p, $q) {
        $R = 6371; // Earth radius in km

        $dLat = (($q[0] - $p[0]) * pi() / 180);
        $dLon = (($q[1] - $p[1]) * pi() / 180);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($p[0] * pi() / 180) * cos($q[0] * pi() / 180) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }
}