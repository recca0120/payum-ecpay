<?php

use Mockery as m;
use PayumTW\Ecpay\Bridge\Ecpay\Device;
use PayumTW\Ecpay\Bridge\Ecpay\IsCollection;
use PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsSubType;

class EcpayLogisticsTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_cvs_map()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new EcpayLogistics();
        $api->HashKey = '5294y06JbISpM5x9';
        $api->HashIV = 'v77hoKGq4kWxNNIS';
        $api->Send = [
            'MerchantID'       => '2000132',
            'MerchantTradeNo'  => 'MerchantTradeNo',
            'LogisticsSubType' => LogisticsSubType::UNIMART_C2C,
            'IsCollection'     => IsCollection::NO,
            'ServerReplyURL'   => 'http://dev/',
            'ExtraData'        => 'ExtraData',
            'Device'           => Device::PC,
        ];

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertTrue(is_array($api->CvsMap()));
    }
}
