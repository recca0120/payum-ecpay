<?php

namespace PayumTW\Ecpay\Tests;

use Mockery as m;
use PayumTW\Ecpay\EcpayApi;
use PHPUnit\Framework\TestCase;
use ECPay_ActionType;
use ECPay_PaymentMethod;

class EcpayApiTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testCreateTransaction()
    {
        $api = new EcpayApi(
            $options = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => false,
            ],
            $httpClient = m::mock('Payum\Core\HttpClientInterface'),
            $message = m::mock('Http\Message\MessageFactory'),
            $sdk = m::mock('PayumTW\Ecpay\Bridge\Ecpay\AllInOne')
        );

        $sdk->Send = [
            'ReturnURL' => null,
            'MerchantTradeNo' => null,
            'MerchantTradeDate' => null,
            'TotalAmount' => null,
            'TradeDesc' => null,
            'ChoosePayment' => null,
            'Items' => null,
        ];
        $sdk->shouldReceive('CheckOutString')->once();
        $sdk->shouldReceive('formToArray')->once();

        $api->createTransaction($params = [
            'ReturnURL' => 'http://www.allpay.com.tw/receive.php',
            'MerchantTradeNo' => 'Test'.time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => 2000,
            'TradeDesc' => 'good to drink',
            'ChoosePayment' => ECPay_PaymentMethod::ALL,
            'Items' => [
                [
                    'Name' => '歐付寶黑芝麻豆漿',
                    'Price' => 2000,
                    'Currency' => '元',
                    'Quantity' => 1,
                    'URL' => 'dedwed',
                ],
            ],
        ]);

        $this->assertSame(array_merge($params, [
            'DeviceSource' => 'P',
        ]), $sdk->Send);
    }

    public function testCancelTransaction()
    {
        $api = new EcpayApi(
            $options = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => false,
            ],
            $httpClient = m::mock('Payum\Core\HttpClientInterface'),
            $message = m::mock('Http\Message\MessageFactory'),
            $sdk = m::mock('PayumTW\Ecpay\Bridge\Ecpay\AllInOne')
        );

        $sdk->Action = [
            'MerchantTradeNo' => '',
            'TradeNo' => '',
            'Action' => ECPay_ActionType::C,
            'TotalAmount' => 0,
        ];

        $sdk->shouldReceive('DoAction')->once();

        $api->cancelTransaction($params = [
            'MerchantTradeNo' => '12345',
            'TradeNo' => '12345',
            'TotalAmount' => 0,
        ]);

        $this->assertSame(array_merge($sdk->Action, $params), $sdk->Action);
    }

    public function testRefundTransaction()
    {
        $api = new EcpayApi(
            $options = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => false,
            ],
            $httpClient = m::mock('Payum\Core\HttpClientInterface'),
            $message = m::mock('Http\Message\MessageFactory'),
            $sdk = m::mock('PayumTW\Ecpay\Bridge\Ecpay\AllInOne')
        );

        $sdk->ChargeBack = [
            'MerchantTradeNo' => '',
            'TradeNo' => '',
            'ChargeBackTotalAmount' => 0,
            'Remark' => '',
        ];

        $sdk->shouldReceive('AioChargeback')->once();

        $api->refundTransaction($params = [
            'MerchantTradeNo' => '12345',
            'TradeNo' => '12345',
            'ChargeBackTotalAmount' => 0,
            'Remark' => '',
        ]);

        $this->assertSame(array_merge($sdk->ChargeBack, $params), $sdk->ChargeBack);
    }

    public function testGetTransactionData()
    {
        $api = new EcpayApi(
            $options = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => false,
            ],
            $httpClient = m::mock('Payum\Core\HttpClientInterface'),
            $message = m::mock('Http\Message\MessageFactory'),
            $sdk = m::mock('PayumTW\Ecpay\Bridge\Ecpay\AllInOne')
        );

        $sdk->Query = [
            'MerchantTradeNo' => '',
            'TimeStamp' => '',
        ];

        $sdk->shouldReceive('QueryTradeInfo')->once();

        $api->getTransactionData($params = [
            'MerchantTradeNo' => '5832985816073',
        ]);

        $this->assertSame(array_merge($sdk->Query, $params), $sdk->Query);
    }
}
