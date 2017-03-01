<?php

namespace PayumTW\Ecpay\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use PayumTW\Ecpay\EcpayLogisticsApi;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Bridge\Ecpay\IsCollection;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsType;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsSubType;

class EcpayLogisticsApiTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testCreateCVSMapTransaction()
    {
        $api = new EcpayLogisticsApi(
            $options = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => false,
            ],
            $httpClient = m::mock('Payum\Core\HttpClientInterface'),
            $message = m::mock('Http\Message\MessageFactory'),
            $sdk = m::mock('PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics')
        );

        $sdk->Send = [];

        $sdk->shouldReceive('CvsMap')->once()->andReturn($cvsMap = []);
        $sdk->shouldReceive('formToArray')->once()->with($cvsMap);

        $api->createCvsMapTransaction($params = [
            'GoodsAmount' => 0,
            'ServerReplyURL' => 'foo',
            'MerchantTradeNo' => 'foo',
            'MerchantTradeDate' => 'foo',
            'LogisticsSubType' => 'foo',
            'IsCollection' => 'foo',
        ]);

        $this->assertSame([
            'MerchantTradeNo' => 'foo',
            'LogisticsSubType' => 'foo',
            'IsCollection' => 'foo',
            'ServerReplyURL' => 'foo',
            'ExtraData' => '',
            'Device' => 0,
        ], $sdk->Send);
    }

    public function testCreateTransaction()
    {
        $api = new EcpayLogisticsApi(
            $options = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => false,
            ],
            $httpClient = m::mock('Payum\Core\HttpClientInterface'),
            $message = m::mock('Http\Message\MessageFactory'),
            $sdk = m::mock('PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics')
        );

        $sdk->shouldReceive('BGCreateShippingOrder')->once();

        $api->createTransaction($params = [
            'MerchantTradeNo' => 'MerchantTradeNo',
            'MerchantTradeDate' => 'MerchantTradeDate',
            'LogisticsType' => 'LogisticsType',
            'LogisticsSubType' => 'LogisticsSubType',
            'GoodsAmount' => 1,
            'CollectionAmount' => 1,
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
        ]);

        $this->assertSame(array_merge([
            'MerchantID' => '2000132',
        ], $params), $sdk->Send);
    }
}
