# randomorg
PHP Implementation of Random.org's  JSON-RPC API

# Installation with Composer
```php composer.phar require defiant/randomorg```
or add it in your composer.json file.

## Usage
```
$apiKey = '00000000-0000-0000-0000-000000000000';
$random = new RandomOrg\Random($apiKey);

// Simple method
// following functions returns 52 random non-repeating numbers between 1-52

$result = $random->generateIntegers(52, 1, 52, false);

// Signed methods
// following functions returns the above with signed data
$result = $random->generateIntegers(52, 1, 52, false, 10, true);

// Verify Signature
$verified = $random->verifySignature($result['result']['random'], $result['result']['signature']);
```

##About API Keys
You can get your own API key at https://api.random.org/api-keys
__Api Key (00000000-0000-0000-0000-000000000000) used in these examples will be disabled
when the beta ends. Get your api key at https://api.random.org/api-keys__

There are three methods where you can set the api key in your class.

First in the constructor:
```
$apiKey = '00000000-0000-0000-0000-000000000000';
$random = new RandomOrg\Random($apiKey);
```

Secondly you can set it directly your class 
```
protected $apiKey = '00000000-0000-0000-0000-000000000000';
```

or you can use the setApi method
```
$apiKey = '00000000-0000-0000-0000-000000000000';
$random = new RandomOrg\Random();
$random->setApiKey($apiKey);
```

Note: Method names are the same with simple method names on the API. To make signed request an optional boolean parameter is needed as the final argument.

Currently supports simple and signed methods below. For more information see https://api.random.org/json-rpc/1/

- generateIntegers
- generateDecimalFractions
- generateGaussians
- generateStrings
- generateUUIDs
- generateBlobs
- getUsage
- generateSignedIntegers
- generateSignedDecimalFractions
- generateSignedGaussians
- generateSignedStrings
- generateSignedUUIDs
- generateSignedBlobs
- verifySignature
