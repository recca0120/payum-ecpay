<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\StatusAction;

class StatusActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_mark_new()
    {
        $this->validate([], 'markNew');
    }

    public function test_mark_captured_when_creditcard_created_and_success()
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
    }

    public function test_mark_fail_when_rtn_code_is_0()
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

    public function test_mark_pending_when_atm_created_and_success()
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

    public function test_mark_captured_when_action_is_c()
    {
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

    public function test_mark_refunded_when_action_is_r()
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

    public function test_mark_canceled_when_action_is_e()
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
    }

    public function test_mark_canceled_when_action_is_n()
    {
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
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\GetStatusInterface');
        $details = new ArrayObject($input);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request->shouldReceive('getModel')->andReturn($details);

        $action = new StatusAction();
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getModel')->twice();
        $request->shouldHaveReceived($type)->once();
    }
}
