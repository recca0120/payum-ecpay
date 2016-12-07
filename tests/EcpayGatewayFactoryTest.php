<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\EcpayGatewayFactory;

class EcpayGatewayFactoryTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_create_factory()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $httpClient = m::mock('Payum\Core\HttpClientInterface');
        $message = m::mock('Http\Message\MessageFactory');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $gateway = new EcpayGatewayFactory();
        $config = $gateway->createConfig([
            'payum.api'               => false,
            'payum.required_options'  => [],
            'payum.http_client'       => $httpClient,
            'httplug.message_factory' => $message,
            'MerchantID'              => '2000132',
            'HashKey'                 => '5294y06JbISpM5x9',
            'HashIV'                  => 'v77hoKGq4kWxNNIS',
            'sandbox'                 => true,
        ]);

        $api = call_user_func($config['payum.api'], ArrayObject::ensureArrayObject($config));
        $this->assertInstanceOf('PayumTW\Ecpay\Api', $api);
    }
}
