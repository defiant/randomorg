# randomorg
PHP Implementation of Random.org's  JSON-RPC API

# Installation with Composer
```php composer.phar require defiant/randomorg```
or add it in your composer.json file.

## Usage
```
$random = new RandomOrg\Random();

// Simple method
// following functions returns 52 random non-repeating numbers between 1-52

$result = $random->generateIntegers(52, 1, 52, false);

// Signed methods
// following functions returns the above with signed data
$result = $random->generateIntegers(52, 1, 52, false, 10, true);

// Verify Signature
$verified = $random->verifySignature($result['result']['random'], $result['result']['signature']);
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
