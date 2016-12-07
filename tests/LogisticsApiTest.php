<?php

use Mockery as m;
use PayumTW\Ecpay\LogisticsApi;

class LogisticsApiTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_prepare_payment()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $options = [
            'MerchantID' => '2000132',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
            'sandbox' => false,
        ];

        $params = [
            'MerchantTradeNo' => uniqid(),
            'GoodsName' => 'fooGoods',
            'SenderName' => 'fooSender',
            'SenderPhone' => '0912345678',
            'SenderCellPhone' => '0912345678',
            'ReceiverName' => 'foo',
            'ReceiverCellPhone' => '0912345678',
            'Remark' => 'fooRemark',
            'PlatformID' => '',
            'LogisticsSubType' => LogisticsSubType::UNIMART,
            'IsCollection' => IsCollection::NO,
            'ReceiverStoreID' => '010268',
            'ReturnStoreID' => '010268',
            'ReceiverEmail' => 'recca0120@gmail.com',
            'GoodsAmount' => 340,
            'TradeDesc' => 'fooTradeDesc',
            'ServerReplyURL' => 'http://fooServerReplyURL',
        ];

        $httpClient = m::mock('Payum\Core\HttpClientInterface');
        $message = m::mock('Http\Message\MessageFactory');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $api = new LogisticsApi($options, $httpClient, $message);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        // $params = $api->preparePayment($params);
    }

    public function test_parse_result()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $options = [
            'MerchantID' => '2000132',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
            'sandbox' => false,
        ];

        $params = [
            'ResCode' => '1',
            'AllPayLogisticsID' => '25077',
            'BookingNote' => '',
            'CheckMacValue' => '256226145F96A320C7503E550C77700F',
            'CVSPaymentNo' => '',
            'CVSValidationNo' => '',
            'GoodsAmount' => '340',
            'LogisticsSubType' => 'FAMI',
            'LogisticsType' => 'CVS',
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '57CBEEF7D3489',
            'ReceiverAddress' => '',
            'ReceiverCellPhone' => '0922261866',
            'ReceiverEmail' => 'recca0120@gmail.com',
            'ReceiverName' => 'Recca Tsai',
            'ReceiverPhone' => '',
            'RtnCode' => '300',
            'RtnMsg' => '訂單處理中(已收到訂單資料)',
            'UpdateStatusDate' => '2016/09/04 17:52:00',
        ];

        $httpClient = m::mock('Payum\Core\HttpClientInterface');
        $message = m::mock('Http\Message\MessageFactory');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $api = new LogisticsApi($options, $httpClient, $message);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $params = $api->parseResult($params);
    }

    public function test_prepare_map()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $options = [
            'MerchantID' => '2000132',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
            'sandbox' => false,
        ];

        $params = [
            'MerchantTradeNo' => uniqid(),
            'ServerReplyURL' => 'http://fooServerReplyURL',
        ];

        $httpClient = m::mock('Payum\Core\HttpClientInterface');
        $message = m::mock('Http\Message\MessageFactory');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $api = new LogisticsApi($options, $httpClient, $message);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $params = $api->prepareMap($params);
    }
}
