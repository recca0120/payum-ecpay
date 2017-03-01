<?php

namespace PayumTW\Ecpay\Tests\Action;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\StatusAction;

class StatusActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testMarkNew()
    {
        $this->validate([], 'markNew');
    }

    public function testMarkCaptured()
    {
        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'MerchantTradeDate' => '2012/03/15 17:40:58',
            'TotalAmount' => '22000',
            'TradeDesc' => '',
            'ItemName' => '',
            'ReturnURL' => '',
            'ChoosePayment' => '',

            'RtnCode' => '1',
            'RtnMsg' => 'paid',
            'TradeNo' => '201203151740582564',
            'TradeAmt' => '22000',
            'PaymentDate' => '2012/03/15 17:40:58',
            'PaymentType' => 'Credit_CreditCard',
            'PaymentTypeChargeFee' => '25',
            'TradeDate' => '2012/03/15 17:40:58',
            'SimulatePaid' => '0',
        ], 'markCaptured');

        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'TradeNo' => '201203151740582564',
            'Action' => 'C',
            'TotalAmount' => '22000',

            'RtnCode' => '1',
            'RtnMsg' => '',
        ], 'markCaptured');
    }

    public function testMarkFail()
    {
        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'MerchantTradeDate' => '2012/03/15 17:40:58',
            'TotalAmount' => '22000',
            'TradeDesc' => '',
            'ItemName' => '',
            'ReturnURL' => '',
            'ChoosePayment' => '',

            'RtnCode' => '0',
            'RtnMsg' => 'paid',
            'TradeNo' => '201203151740582564',
            'TradeAmt' => '22000',
            'PaymentDate' => '2012/03/15 17:40:58',
            'PaymentType' => 'Credit_CreditCard',
            'PaymentTypeChargeFee' => '25',
            'TradeDate' => '2012/03/15 17:40:58',
            'SimulatePaid' => '0',
        ], 'markFailed');
    }

    public function testMarkPending()
    {
        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'MerchantTradeDate' => '2012/03/15 17:40:58',
            'TotalAmount' => '22000',
            'TradeDesc' => '',
            'ItemName' => '',
            'ReturnURL' => '',
            'ChoosePayment' => '',

            'RtnCode' => '2',
            'RtnMsg' => 'Get VirtualAccount 730_阿狗的環遊世界',
            'TradeNo' => '201203151740582564',
            'TradeAmt' => '22000',
            'PaymentDate' => '2012/03/15 17:40:58',
            'PaymentType' => 'WEBTM_TAISHI',
            'TradeDate' => '2012/03/15 17:40:58',

            // ATM
            'BankCode' => '',
            'vAccount' => '',
            'ExpireDate' => '',

            // CVS, BARCODE
            'PaymentNo' => '',
            'ExpireDate' => '',
            'Barcode1' => '',
            'Barcode2' => '',
            'Barcode3' => '',
        ], 'markPending');
    }

    public function testMarkRefunded()
    {
        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'TradeNo' => '201203151740582564',
            'Action' => 'R',
            'TotalAmount' => '22000',

            'RtnCode' => '1',
            'RtnMsg' => '',
        ], 'markRefunded');
    }

    public function testMarkCanceled()
    {
        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'TradeNo' => '201203151740582564',
            'Action' => 'E',
            'TotalAmount' => '22000',

            'RtnCode' => '1',
            'RtnMsg' => '',
        ], 'markCanceled');

        $this->validate([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => '123456abc',
            'TradeNo' => '201203151740582564',
            'Action' => 'N',
            'TotalAmount' => '22000',

            'RtnCode' => '1',
            'RtnMsg' => '',
        ], 'markCanceled');
    }

    protected function validate($input, $type)
    {
        $action = new StatusAction();
        $request = m::mock('Payum\Core\Request\GetStatusInterface');
        $request->shouldReceive('getModel')->andReturn($details = new ArrayObject($input));
        $request->shouldReceive($type)->once();

        $action->execute($request);
    }
}
