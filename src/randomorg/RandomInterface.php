<?php


namespace randomorg;


interface RandomInterface {

    public function generateIntegers($n, $min, $max, $replacement, $base);
    public function generateDecimalFractions($n, $decimal, $replacement);
    public function generateGaussians($n, $mean, $standardDeviation, $significantDigits);
    public function generateStrings($n, $length, $chars, $replacement);
    public function generateUUIDs($n);
    public function generateBlobs($n, $size, $format = 'base64');
    public function getUsage();
}