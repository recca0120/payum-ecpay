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

    public function test_mark_captured_when_rtn_code_is_1()
    {
        $this->validate(['RtnCode' => '1'], 'markCaptured');
    }

    public function test_mark_captured_when_rtn_code_is_3()
    {
        $this->validate(['RtnCode' => '3'], 'markCaptured');
    }

    public function test_mark_failed_when_rtn_code_is_not_3()
    {
        $this->validate(['RtnCode' => '5'], 'markFailed');
    }

    public function test_mark_refund_when_rtn_code_is_1_and_action_is_r()
    {
        $this->validate(['RtnCode' => '1', 'Action' => 'R'], 'markRefunded');
    }

    public function test_mark_cancel_when_rtn_code_is_1_and_action_is_e()
    {
        $this->validate(['RtnCode' => '1', 'Action' => 'E'], 'markCanceled');
    }

    public function test_mark_cancel_when_rtn_code_is_1_and_action_is_n()
    {
        $this->validate(['RtnCode' => '1', 'Action' => 'N'], 'markCanceled');
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
