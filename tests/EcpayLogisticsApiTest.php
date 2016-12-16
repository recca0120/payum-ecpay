<?php

use Mockery as m;
use PayumTW\Ecpay\EcpayLogisticsApi;
use PayumTW\Ecpay\Bridge\Ecpay\IsCollection;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsType;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsSubType;

class EcpayLogisticsApiTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_create_cvs_map_transaction()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics');
        $options = [
            'MerchantID' => '2000132',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
            'sandbox' => false,
        ];

        $params = [
            'GoodsAmount' => 0,
            'ServerReplyURL' => 'ServerReplyURL',
            'MerchantTradeNo' => 'MerchantTradeNo',
            'MerchantTradeDate' => 'MerchantTradeDate',
            'LogisticsSubType' => 'LogisticsSubType',
            'IsCollection' => 'IsCollection',
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new EcpayLogisticsApi($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $api->createTransaction($params);
        $this->assertSame($params['ServerReplyURL'], $sdk->Send['ServerReplyURL']);
        $this->assertSame($params['MerchantTradeNo'], $sdk->Send['MerchantTradeNo']);
        $this->assertSame($params['LogisticsSubType'], $sdk->Send['LogisticsSubType']);
        $this->assertSame($params['IsCollection'], $sdk->Send['IsCollection']);
        $sdk->shouldHaveReceived('CvsMap')->once();
    }

    public function test_create_transaction()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics');
        $options = [
            'MerchantID' => '2000132',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
            'sandbox' => false,
        ];

        $params = [
            'GoodsAmount' => 1,
            'MerchantTradeNo' => 'MerchantTradeNo',
            'MerchantTradeDate' => 'MerchantTradeDate',
            'LogisticsType' => 'LogisticsType',
            'LogisticsSubType' => 'LogisticsSubType',
            'GoodsAmount' => 'GoodsAmount',
            'CollectionAmount' => 'CollectionAmount',
            'IsCollection' => 'IsCollection',
            'GoodsName' => 'GoodsName',
            'SenderName' => 'SenderName',
            'SenderPhone' => 'SenderPhone',
            'SenderCellPhone' => 'SenderCellPhone',
            'ReceiverName' => 'ReceiverName',
            'ReceiverPhone' => 'ReceiverPhone',
            'ReceiverCellPhone' => 'ReceiverCellPhone',
            'ReceiverEmail' => 'ReceiverEmail',
            'TradeDesc' => 'TradeDesc',
            'ServerReplyURL' => 'ServerReplyURL',
            'LogisticsC2CReplyURL' => 'LogisticsC2CReplyURL',
            'Remark' => 'Remark',
            'PlatformID' => 'PlatformID',
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new EcpayLogisticsApi($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $api->createTransaction($params);
        $this->assertSame($params['MerchantTradeNo'], $sdk->Send['MerchantTradeNo']);
        $this->assertSame($params['MerchantTradeDate'], $sdk->Send['MerchantTradeDate']);
        $this->assertSame($params['LogisticsType'], $sdk->Send['LogisticsType']);
        $this->assertSame($params['LogisticsSubType'], $sdk->Send['LogisticsSubType']);
        $this->assertSame($params['GoodsAmount'], $sdk->Send['GoodsAmount']);
        $this->assertSame($params['CollectionAmount'], $sdk->Send['CollectionAmount']);
        $this->assertSame($params['IsCollection'], $sdk->Send['IsCollection']);
        $this->assertSame($params['GoodsName'], $sdk->Send['GoodsName']);
        $this->assertSame($params['SenderName'], $sdk->Send['SenderName']);
        $this->assertSame($params['SenderPhone'], $sdk->Send['SenderPhone']);
        $this->assertSame($params['SenderCellPhone'], $sdk->Send['SenderCellPhone']);
        $this->assertSame($params['ReceiverName'], $sdk->Send['ReceiverName']);
        $this->assertSame($params['ReceiverPhone'], $sdk->Send['ReceiverPhone']);
        $this->assertSame($params['ReceiverCellPhone'], $sdk->Send['ReceiverCellPhone']);
        $this->assertSame($params['ReceiverEmail'], $sdk->Send['ReceiverEmail']);
        $this->assertSame($params['TradeDesc'], $sdk->Send['TradeDesc']);
        $this->assertSame($params['ServerReplyURL'], $sdk->Send['ServerReplyURL']);
        $this->assertSame($params['LogisticsC2CReplyURL'], $sdk->Send['LogisticsC2CReplyURL']);
        $this->assertSame($params['Remark'], $sdk->Send['Remark']);
        $this->assertSame($params['PlatformID'], $sdk->Send['PlatformID']);

        $sdk->shouldHaveReceived('BGCreateShippingOrder')->once();
    }

    public function test_get_transaction_data()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics');
        $options = [
            'MerchantID' => '2000132',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
            'sandbox' => false,
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new EcpayLogisticsApi($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $params = ['foo' => 'bar'];
        $this->assertSame($params, $api->getTransactionData($params));
        $params = [
            'response' => ['foo' => 'bar'],
        ];
        $this->assertSame($params['response'], $api->getTransactionData($params));
    }
}
