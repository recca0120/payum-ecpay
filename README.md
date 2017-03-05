# Ecpay

[![StyleCI](https://styleci.io/repos/75879524/shield?style=flat)](https://styleci.io/repos/75879524)
[![Build Status](https://travis-ci.org/recca0120/payum-ecpay.svg)](https://travis-ci.org/recca0120/payum-ecpay)
[![Total Downloads](https://poser.pugx.org/payum-tw/ecpay/d/total.svg)](https://packagist.org/packages/payum-tw/ecpay)
[![Latest Stable Version](https://poser.pugx.org/payum-tw/ecpay/v/stable.svg)](https://packagist.org/packages/payum-tw/ecpay)
[![Latest Unstable Version](https://poser.pugx.org/payum-tw/ecpay/v/unstable.svg)](https://packagist.org/packages/payum-tw/ecpay)
[![License](https://poser.pugx.org/payum-tw/ecpay/license.svg)](https://packagist.org/packages/payum-tw/ecpay)
[![Monthly Downloads](https://poser.pugx.org/payum-tw/ecpay/d/monthly)](https://packagist.org/packages/payum-tw/ecpay)
[![Daily Downloads](https://poser.pugx.org/payum-tw/ecpay/d/daily)](https://packagist.org/packages/payum-tw/ecpay)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/recca0120/payum-ecpay/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/recca0120/payum-ecpay/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/recca0120/payum-ecpay/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/recca0120/payum-ecpay/?branch=master)

The Payum extension to rapidly build new extensions.

1. Create new project

```bash
$ composer create-project payum-tw/ecpay
```

2. Replace all occurrences of `payum` with your vendor name. It may be your github name, for now let's say you choose: `ecpay`.
3. Replace all occurrences of `ecpay` with a payment gateway name. For example Stripe, Paypal etc. For now let's say you choose: `ecpay`.
4. Register a gateway factory to the payum's builder and create a gateway:

```php
<?php

use Payum\Core\PayumBuilder;
use Payum\Core\GatewayFactoryInterface;

$defaultConfig = [];

$payum = (new PayumBuilder)
    ->addGatewayFactory('ecpay', function(array $config, GatewayFactoryInterface $coreGatewayFactory) {
        return new \PayumTW\Ecpay\EcpayGatewayFactory($config, $coreGatewayFactory);
    })

    ->addGateway('ecpay', [
        'factory'     => 'ecpay',
        'MerchantID'  => '2000132',
        'HashKey'     => '5294y06JbISpM5x9',
        'HashIV'      => 'v77hoKGq4kWxNNIS',
        'sandbox'     => true,
    ])

    ->getPayum();
```

5. While using the gateway implement all method where you get `Not implemented` exception:

```php
<?php

use Payum\Core\Request\Capture;

$ecpay = $payum->getGateway('ecpay');

$model = new \ArrayObject([
  // ...
]);

$ecpay->execute(new Capture($model));
```

## Resources

* [Documentation](https://github.com/Payum/Payum/blob/master/src/Payum/Core/Resources/docs/index.md)
* [Questions](http://stackoverflow.com/questions/tagged/payum)
* [Issue Tracker](https://github.com/Payum/Payum/issues)
* [Twitter](https://twitter.com/payumphp)

## License

Skeleton is released under the [MIT License](LICENSE).
