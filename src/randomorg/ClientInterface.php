<?php

namespace RandomOrg;

interface ClientInterface
{
    public function prepareRequest($method, array $params);
    public function makeRequest($request);
    public function getResponse(array $response);
}
