<?php

namespace RandomOrg;

/**
 * Class Random
 * @package RandomOrg
 */
class Random implements RandomInterface
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var string
     */
    public $url = 'https://api.random.org/json-rpc/1/invoke';

    /**
     * Get your at https://api.random.org/api-keys
     * @var string
     */
    protected $apiKey = '';

    /* Simple Methods */
    const INTEGERS = 'generateIntegers';
    const DECIMAL_FRACTIONS = 'generateDecimalFractions';
    const GAUSSIANS = 'generateGaussians';
    const STRINGS = 'generateStrings';
    const UUIDS = 'generateUUIDs';
    const BLOBS = 'generateBlobs';

    /* Signed Methods */
    const SIGNED_INTEGERS = 'generateSignedIntegers';
    const SIGNED_DECIMAL_FRACTIONS = 'generateSignedDecimalFractions';
    const SIGNED_GAUSSIANS = 'generateSignedGaussians';
    const SIGNED_STRINGS = 'generateSignedStrings';
    const SIGNED_UUIDS = 'generateSignedUUIDs';
    const SIGNED_BLOBS = 'generateSignedBlobs';

    /**
     * @param string apiKey
     * @param ClientInterface $client
     */
    public function __construct($apiKey = '', ClientInterface $client = null)
    {
        if (!$client) {
            $this->client = new Client($this->url);
        } else {
            $this->client = $client;
        }

        if ($apiKey) {
            $this->apiKey = $apiKey;
        }
    }

    /**
     * @param $n
     * @param $min
     * @param $max
     * @param bool $replacement
     * @param int $base
     * @param bool $signed
     * @return mixed
     * @throws RuntimeException
     * @throws \Exception
     */
    public function generateIntegers($n, $min, $max, $replacement = true, $base = 10, $signed = false)
    {
        $params = [
            'n' => $n,
            'min' => $min,
            'max' => $max,
            'replacement' => $replacement,
            'base' => $base,
        ];

        $method = $signed ? self::SIGNED_INTEGERS : self::INTEGERS;

        return $this->query($method, $params);
    }

    /**
     * @param $n
     * @param int $decimalPlaces
     * @param bool $replacement
     * @param bool $signed
     * @return mixed
     * @throws \Exception
     */
    public function generateDecimalFractions($n, $decimalPlaces = 2, $replacement = true, $signed = false)
    {
        if ($decimalPlaces > 20 || $decimalPlaces < 1) {
            $decimalPlaces = 2;
        }

        $params = [
            'n' => $n,
            'decimalPlaces' => $decimalPlaces,
            'replacement' => $replacement
        ];

        $method = $signed ? self::SIGNED_DECIMAL_FRACTIONS : self::DECIMAL_FRACTIONS;

        return $this->query($method, $params);
    }

    /**
     * @param $n
     * @param $mean
     * @param $standardDeviation
     * @param int $significantDigits
     * @param bool $signed
     * @return mixed
     * @throws \Exception
     */
    public function generateGaussians($n, $mean, $standardDeviation, $significantDigits = 2, $signed = false)
    {
        if ($significantDigits > 20 || $significantDigits < 1) {
            $significantDigits = 2;
        }

        $params = [
            'n' => $n,
            'mean' => $mean,
            'standardDeviation' => $standardDeviation,
            'significantDigits' => $significantDigits
        ];

        $method = $signed ? self::SIGNED_GAUSSIANS : self::GAUSSIANS;

        return $this->query($method, $params);
    }

    /**
     * @param $n
     * @param $length
     * @param string $chars
     * @param bool $replacement
     * @param bool signed
     * @return mixed
     * @throws \Exception
     */
    public function generateStrings($n, $length, $chars = '', $replacement = true, $signed = false)
    {
        if (!$chars) {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        }

        $params = [
            'n' => $n,
            'length' => $length,
            'characters' => $chars,
            'replacement' => $replacement
        ];

        $method = $signed ? self::SIGNED_STRINGS : self::STRINGS;

        return $this->query($method, $params);
    }

    /**
     * @param int $n
     * @param bool $signed
     * @return mixed
     * @throws \Exception
     */
    public function generateUUIDs($n, $signed = false)
    {
        $params = ['n' => $n];

        $method = $signed ? self::SIGNED_UUIDS : self::UUIDS;

        return $this->query($method, $params);
    }

    /**
     * @param $n
     * @param $size
     * @param string $format
     * @param bool $signed
     * @return mixed
     * @throws \Exception
     */
    public function generateBlobs($n, $size, $format = 'base64', $signed = false)
    {
        $acceptedValues = ['base64', 'hex'];

        if (!in_array($format, $acceptedValues)) {
            $format = 'base64';
        }

        $params = [
            'n' => $n,
            'size' => $size,
            'format' => $format
        ];

        $method = $signed ? self::SIGNED_BLOBS : self::BLOBS;

        return $this->query($method, $params);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUsage()
    {
        return $this->query('getUsage');
    }


    /**
     * @param array $random
     * @param $signature
     * @return bool
     * @throws \Exception
     */
    public function verifySignature(array $random, $signature)
    {
        $params = [
          'random' => $random,
          'signature' => $signature
        ];

        $result = $this->query('verifySignature', $params);

        return (bool) $result['result']['authenticity'];
    }


    /**
     * @param $method
     * @param array $params
     * @return mixed
     * @throws RandomOrgException
     */
    protected function query($method, $params = [])
    {
        // All other methods require an API key except verifySignature
        // this method seems to work without it
        if ($method != 'verifySignature') {
            $params = array_merge($params, ['apiKey' => $this->apiKey]);
        }

        $req = $this->client->prepareRequest($method, $params);
        $res = $this->client->makeRequest($req);

        if (isset($res['error'])) {
            throw new RandomOrgException('Random.org exception (' . $res['error']['code'] . '): ' . $res['error']['message']);
        }

        return $res;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}
