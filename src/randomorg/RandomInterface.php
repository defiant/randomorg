<?php

namespace RandomOrg;

interface RandomInterface
{
    /*
     * Simple Methods
     * */
    public function generateIntegers($n, $min, $max, $replacement, $base, $signed);
    public function generateDecimalFractions($n, $decimal, $replacement, $signed);
    public function generateGaussians($n, $mean, $standardDeviation, $significantDigits, $signed);
    public function generateStrings($n, $length, $chars, $replacement, $signed);
    public function generateUUIDs($n, $signed);
    public function generateBlobs($n, $size, $format = 'base64', $signed);
    public function getUsage();
    public function verifySignature(array $random, $signature);
}
