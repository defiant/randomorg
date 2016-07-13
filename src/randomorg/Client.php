<?php

namespace RandomOrg;

/**
 * Class Client
 * Simple JSON RPC Client
 *
 * @package RandomOrg
 */
class Client implements ClientInterface
{
    /**
     * @var string
     */
    public $url = '';

    /**
     * @var bool
     */
    public $verifyPeer = true;

    /**
     * @var int
     */
    public $timeout = 10;

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var array
     */
    protected $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    /**
     * @var resource
     */
    protected $ch;

    /**
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        // init curl
        $this->ch = curl_init($this->url);
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }


    /**
     * prepare a json rpc request array
     *
     * @param $method
     * @param array $params
     * @return array
     */
    public function prepareRequest($method, array $params = [])
    {
        $request = [
            'jsonrpc' => '2.0',
            'method'  => $method,
            'id'      => mt_rand()
        ];

        $request['params'] = $params ? $params : [];

        /*$request['params'] = array_merge($request['params'], $this->params);*/
        return $request;
    }

    /**
     * make the request to the server and get response
     *
     * @param $request
     * @return mixed
     * @throws RuntimeException
     * @throws \Exception
     */
    public function makeRequest($request)
    {
        $optionsSet = curl_setopt_array($this->ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_USERAGENT => 'JSON-RPC Random.org PHP Client',
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => $this->verifyPeer,
            CURLOPT_POSTFIELDS => json_encode($request)
        ]);

        if (!$optionsSet) {
            throw new \Exception('Cannot set curl options');
        }

        $responseBody = curl_exec($this->ch);
        $responseCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if (curl_errno($this->ch)) {
            throw new RuntimeException(curl_error($this->ch));
        }

        if ($responseCode === 401 || $responseCode === 403) {
            throw new RuntimeException('Access denied');
        }

        $response = json_decode($responseBody, true);

        if ($this->debug) {
            echo('==> Request: '.PHP_EOL.json_encode($request, JSON_PRETTY_PRINT));
            echo('==> Response: '.PHP_EOL.json_encode($response, JSON_PRETTY_PRINT));
        }

        return $response;
    }

    /**
     * Get the response from the server
     * if there are API error pass them to error handler function
     *
     * @param array $response
     * @return null
     * @throws BadFunctionCallException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getResponse(array $response)
    {
        if (isset($response['error']['code'])) {
            $this->handleRpcErrors($response['error']);
        }

        return isset($response['result']) ? $response['result'] : null;
    }

    /**
     * Process JSON-RPC errors
     *
     * @param $error
     * @throws BadFunctionCallException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function handleRpcErrors($error)
    {
        switch ($error['code']) {
            case -32600:
                throw new \InvalidArgumentException('Invalid Request: '. $error['message']);
            case -32601:
                throw new \BadFunctionCallException('Procedure not found: '. $error['message']);
            case -32602:
                throw new \InvalidArgumentException('Invalid arguments: '. $error['message']);
            case -32603:
                throw new \RuntimeException('Internal Error: '. $error['message']);
            default:
                throw new \RuntimeException('Invalid request/response: '. $error['message'], $error['code']);
        }
    }
}
