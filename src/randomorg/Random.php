<?php
namespace RandomOrg;

/**
 * Class Random
 * @package RandomOrg
 */
class Random implements RandomInterface{

    /**
     * @var Client
     */
    public $client;

    /**
     * @var string
     */
    public $url = 'https://api.random.org/json-rpc/1/invoke';

    /**
     * @var string
     */
    protected $apiKey = '426c43ad-63de-4e3d-bfe3-bc89c1ac9005';

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = new Client($this->url, ['apiKey' => $this->apiKey]);
    }

    /**
     * @param $n
     * @param $min
     * @param $max
     * @param bool $replacement
     * @param int $base
     * @return mixed
     * @throws RuntimeException
     * @throws \Exception
     */
    public function generateIntegers($n, $min, $max, $replacement = true, $base = 10)
    {
        $params = [
            'n' => $n,
            'min' => $min,
            'max' => $max,
            'replacement' => $replacement,
            'base' => $base,
        ];

        return $this->query('generateIntegers', $params);
    }

    /**
     * @param $n
     * @param int $decimalPlaces
     * @param $replacement
     * @return mixed
     * @throws \Exception
     */
    public function generateDecimalFractions($n, $decimalPlaces = 2, $replacement)
    {
        if($decimalPlaces > 20 || $decimalPlaces < 1){
            $decimalPlaces = 2;
        }

        $params = [
            'n' => $n,
            'decimalPlaces' => $decimalPlaces,
            'replacement' => $replacement
        ];

        return $this->query('generateDecimalFractions', $params);
    }

    /**
     * @param $n
     * @param $mean
     * @param $standardDeviation
     * @param int $significantDigits
     * @return mixed
     * @throws \Exception
     */
    public function generateGaussians($n, $mean, $standardDeviation, $significantDigits = 2) {

        if($significantDigits > 20 || $significantDigits < 1) {
            $significantDigits = 2;
        }

        $params = [
            'n' => $n,
            'mean' => $mean,
            'standardDeviation' => $standardDeviation,
            'significantDigits' => $significantDigits
        ];

        return $this->query('generateGaussians', $params);
    }

    /**
     * @param $n
     * @param $length
     * @param string $chars
     * @param bool $replacement
     * @return mixed
     * @throws \Exception
     */
    public function generateStrings($n, $length, $chars = '', $replacement = true)
    {
        if(!$chars){
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        }

        $params = [
            'n' => $n,
            'length' => $length,
            'characters' => $chars,
            'replacement' => $replacement
        ];

        return $this->query('generateStrings', $params);
    }

    /**
     * @param $n
     * @return mixed
     * @throws \Exception
     */
    public function generateUUIDs($n)
    {
        $params = ['n' => $n];

        return $this->query('generateUUIDs', $params);

    }

    /**
     * @param $n
     * @param $size
     * @param string $format
     * @return mixed
     * @throws \Exception
     */
    public function generateBlobs($n, $size, $format = 'base64')
    {
        $acceptedValues = ['base64', 'hex'];

        if(!in_array($format, $acceptedValues)){
            $format = 'base64';
        }

        $params = [
            'n' => $n,
            'size' => $size,
            'format' => $format
        ];

        return $this->query('generateBlobs', $params);
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
     * @param $method
     * @param array $params
     * @return mixed
     * @throws RuntimeException
     * @throws \Exception
     */
    protected function query($method, $params = [])
    {
        $req = $this->client->prepareRequest($method, $params);
        $res = $this->client->makeRequest($req);

        if(isset($res['error'])){
            throw new \Exception('Random.org exception (' . $res['error']['code'] . '): ' . $res['error']['message']);
        }

        return $res;
    }
}