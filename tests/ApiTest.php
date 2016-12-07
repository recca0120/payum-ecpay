<?php

use Mockery as m;
use PayumTW\Ecpay\Api;
use PayumTW\Ecpay\Bridge\Ecpay\ActionType;
use PayumTW\Ecpay\Bridge\Ecpay\EncryptType;
use PayumTW\Ecpay\Bridge\Ecpay\ExtraPaymentInfo;
use PayumTW\Ecpay\Bridge\Ecpay\InvoiceState;
use PayumTW\Ecpay\Bridge\Ecpay\PaymentMethod;
use PayumTW\Ecpay\Bridge\Ecpay\PaymentMethodItem;

class ApiTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
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
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\AllInOne');

        $sdk->Send = [
            'ReturnURL'         => '',
            'ClientBackURL'     => '',
            'OrderResultURL'    => '',
            'MerchantTradeNo'   => '',
            'MerchantTradeDate' => '',
            'PaymentType'       => 'aio',
            'TotalAmount'       => '',
            'TradeDesc'         => '',
            'ChoosePayment'     => PaymentMethod::ALL,
            'Remark'            => '',
            'ChooseSubPayment'  => PaymentMethodItem::None,
            'NeedExtraPaidInfo' => ExtraPaymentInfo::No,
            'DeviceSource'      => '',
            'IgnorePayment'     => '',
            'PlatformID'        => '',
            'InvoiceMark'       => InvoiceState::No,
            'Items'             => [],
            'EncryptType'       => EncryptType::ENC_MD5,
        ];

        $options = [
            'MerchantID' => '2000132',
            'HashKey'    => '5294y06JbISpM5x9',
            'HashIV'     => 'v77hoKGq4kWxNNIS',
            'sandbox'    => false,
        ];

        $params = [
            'ReturnURL'         => 'http://www.allpay.com.tw/receive.php',
            'MerchantTradeNo'   => 'Test'.time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount'       => 2000,
            'TradeDesc'         => 'good to drink',
            'ChoosePayment'     => PaymentMethod::ALL,
            'Items'             => [
                [
                    'Name'     => '歐付寶黑芝麻豆漿',
                    'Price'    => 2000,
                    'Currency' => '元',
                    'Quantity' => 1,
                    'URL'      => 'dedwed',
                ],
            ],
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new Api($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $api->createTransaction($params);
        $this->assertSame($options['HashKey'], $sdk->HashKey);
        $this->assertSame($options['HashIV'], $sdk->HashIV);
        $this->assertSame($options['MerchantID'], $sdk->MerchantID);
        $this->assertSame($api->getApiEndpoint('AioCheckOut'), $sdk->ServiceURL);
        $this->assertSame($params['ReturnURL'], $sdk->Send['ReturnURL']);
        $sdk->shouldHaveReceived('CheckOut')->once();
    }

    public function test_cancel_transaction()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\AllInOne');

        $sdk->Action = [
            'MerchantTradeNo' => '',
            'TradeNo'         => '',
            'Action'          => ActionType::C,
            'TotalAmount'     => 0,
        ];

        $options = [
            'MerchantID' => '2000132',
            'HashKey'    => '5294y06JbISpM5x9',
            'HashIV'     => 'v77hoKGq4kWxNNIS',
            'sandbox'    => false,
        ];

        $params = [
            'MerchantTradeNo' => '12345',
            'TradeNo'         => '12345',
            'TotalAmount'     => 0,
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new Api($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $api->cancelTransaction($params);
        $this->assertSame($options['HashKey'], $sdk->HashKey);
        $this->assertSame($options['HashIV'], $sdk->HashIV);
        $this->assertSame($options['MerchantID'], $sdk->MerchantID);
        $this->assertSame($api->getApiEndpoint('DoAction'), $sdk->ServiceURL);
        $this->assertSame($params['MerchantTradeNo'], $sdk->Action['MerchantTradeNo']);
        $this->assertSame($params['TradeNo'], $sdk->Action['TradeNo']);
        $this->assertSame($params['TotalAmount'], $sdk->Action['TotalAmount']);
        $sdk->shouldHaveReceived('DoAction')->once();
    }

    public function test_refund_transaction()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\AllInOne');

        $sdk->ChargeBack = [
            'MerchantTradeNo'       => '',
            'TradeNo'               => '',
            'ChargeBackTotalAmount' => 0,
            'Remark'                => '',
        ];

        $options = [
            'MerchantID' => '2000132',
            'HashKey'    => '5294y06JbISpM5x9',
            'HashIV'     => 'v77hoKGq4kWxNNIS',
            'sandbox'    => false,
        ];

        $params = [
            'MerchantTradeNo'       => '12345',
            'TradeNo'               => '12345',
            'ChargeBackTotalAmount' => 0,
            'Remark'                => '',
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new Api($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $api->refundTransaction($params);
        $this->assertSame($options['HashKey'], $sdk->HashKey);
        $this->assertSame($options['HashIV'], $sdk->HashIV);
        $this->assertSame($options['MerchantID'], $sdk->MerchantID);
        $this->assertSame($api->getApiEndpoint('AioChargeback'), $sdk->ServiceURL);
        $this->assertSame($params['MerchantTradeNo'], $sdk->ChargeBack['MerchantTradeNo']);
        $this->assertSame($params['TradeNo'], $sdk->ChargeBack['TradeNo']);
        $this->assertSame($params['ChargeBackTotalAmount'], $sdk->ChargeBack['ChargeBackTotalAmount']);
        $sdk->shouldHaveReceived('AioChargeback')->once();
    }

    public function test_get_transaction_data_when_response_from_request()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\AllInOne');

        $sdk->ChargeBack = [
            'MerchantTradeNo'       => '',
            'TradeNo'               => '',
            'ChargeBackTotalAmount' => 0,
            'Remark'                => '',
        ];

        $options = [
            'MerchantID' => '2000132',
            'HashKey'    => '5294y06JbISpM5x9',
            'HashIV'     => 'v77hoKGq4kWxNNIS',
            'sandbox'    => false,
        ];

        $params = [
            'response' => [
                'MerchantID'           => '2000132',
                'MerchantTradeNo'      => '57CBC66A39F82',
                'PayAmt'               => '340',
                'PaymentDate'          => '2016/09/04 15:03:08',
                'PaymentType'          => 'Credit_CreditCard',
                'PaymentTypeChargeFee' => '3',
                'RedeemAmt'            => '0',
                'RtnCode'              => '1',
                'RtnMsg'               => 'Succeeded',
                'SimulatePaid'         => '0',
                'TradeAmt'             => '340',
                'TradeDate'            => '2016/09/04 14:59:13',
                'TradeNo'              => '1609041459136128',
                'CheckMacValue'        => '6812D213BF2C5B9377EBF101607BF2DF',
            ],
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new Api($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertSame($params['response'], $api->getTransactionData($params));
        $this->assertSame($options['HashKey'], $sdk->HashKey);
        $this->assertSame($options['HashIV'], $sdk->HashIV);
        $this->assertSame($options['MerchantID'], $sdk->MerchantID);
    }

    public function test_get_transaction_data_when_response_from_request_and_verify_hash_is_fail()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\AllInOne');

        $sdk->ChargeBack = [
            'MerchantTradeNo'       => '',
            'TradeNo'               => '',
            'ChargeBackTotalAmount' => 0,
            'Remark'                => '',
        ];

        $options = [
            'MerchantID' => '2000132',
            'HashKey'    => '5294y06JbISpM5x9',
            'HashIV'     => 'v77hoKGq4kWxNNIS',
            'sandbox'    => false,
        ];

        $params = [
            'response' => [
                'MerchantID'           => '2000132',
                'MerchantTradeNo'      => '57CBC66A39F82',
                'PayAmt'               => '340',
                'PaymentDate'          => '2016/09/04 15:03:08',
                'PaymentType'          => 'Credit_CreditCard',
                'PaymentTypeChargeFee' => '3',
                'RedeemAmt'            => '0',
                'RtnCode'              => '1',
                'RtnMsg'               => 'Succeeded',
                'SimulatePaid'         => '0',
                'TradeAmt'             => '340',
                'TradeDate'            => '2016/09/04 14:59:13',
                'TradeNo'              => '1609041459136128',
                'CheckMacValue'        => '',
            ],
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $sdk->shouldReceive('CheckOutFeedback')->andThrow('Exception');

        $api = new Api($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertSame([
            'RtnCode' => '10400002',
        ], $api->getTransactionData($params));
        $this->assertSame($options['HashKey'], $sdk->HashKey);
        $this->assertSame($options['HashIV'], $sdk->HashIV);
        $this->assertSame($options['MerchantID'], $sdk->MerchantID);

        $sdk->shouldHaveReceived('CheckOutFeedback')->once();
    }

    public function test_get_transaction_data_when_response_from_query_info()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $httpClient = m::spy('Payum\Core\HttpClientInterface');
        $message = m::spy('Http\Message\MessageFactory');
        $sdk = m::spy('PayumTW\Ecpay\Bridge\Ecpay\AllInOne');

        $sdk->Query = [
            'MerchantTradeNo' => '',
            'TimeStamp'       => '',
        ];

        $options = [
            'MerchantID' => '2000132',
            'HashKey'    => '5294y06JbISpM5x9',
            'HashIV'     => 'v77hoKGq4kWxNNIS',
            'sandbox'    => false,
        ];

        $params = [
            'MerchantTradeNo' => '5832985816073',
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $api = new Api($options, $httpClient, $message, $sdk);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $api->getTransactionData($params);
        $this->assertSame($options['HashKey'], $sdk->HashKey);
        $this->assertSame($options['HashIV'], $sdk->HashIV);
        $this->assertSame($options['MerchantID'], $sdk->MerchantID);
        $this->assertSame($api->getApiEndpoint('QueryTradeInfo'), $sdk->ServiceURL);
        $sdk->shouldHaveReceived('QueryTradeInfo')->once();
    }
}
