<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\StatusLogisticsAction;

class StatusLogisticsActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_mark_unknown()
    {
        $this->validate([], 'markUnknown');
    }

    public function test_mark_failed_when_error_message_exists()
    {
        $this->validate(['ErrorMessage' => '1'], 'markFailed');
    }

    public function test_mark_refunded_when_rtn_code_is_1_and_all_pay_logistics_id_exists()
    {
        $this->validate(['RtnCode' => '1', 'RtnMerchantTradeNo' => '1'], 'markRefunded');
    }

    public function test_mark_failed_when_rtn_code_is_1()
    {
        $this->validate(['RtnCode' => '1'], 'markCaptured');
    }

    public function test_mark_failed_when_rtn_code_is_0()
    {
        $this->validate(['RtnCode' => '0'], 'markFailed');
    }

    public function test_mark_captured_when_res_code_is_1()
    {
        $this->validate(['ResCode' => '1'], 'markCaptured');
    }

    public function test_mark_failed_when_res_code_is_1()
    {
        $this->validate(['ResCode' => '0'], 'markFailed');
    }

    public function test_mark_captured_when_cvs_store_id_exists()
    {
        $this->validate(['CVSStoreID' => '1'], 'markCaptured');
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

        $action = new StatusLogisticsAction();
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
