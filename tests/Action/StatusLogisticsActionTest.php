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

    public function test_mark_captured_when_rtn_code_is_300()
    {
        $this->validate(['RtnCode' => '300'], 'markCaptured');
    }

    public function test_mark_captured_when_rtn_code_is_310()
    {
        $this->validate(['RtnCode' => '310'], 'markCaptured');
    }

    public function test_mark_captured_when_rtn_code_is_311()
    {
        $this->validate(['RtnCode' => '311'], 'markCaptured');
    }

    public function test_mark_captured_when_rtn_code_is_325()
    {
        $this->validate(['RtnCode' => '325'], 'markCaptured');
    }

    public function test_mark_captured_when_rtn_code_is_2000()
    {
        $this->validate(['RtnCode' => '2000'], 'markCaptured');
    }

    public function test_mark_captured_when_rtn_code_is_2001()
    {
        $this->validate(['RtnCode' => '2001'], 'markCaptured');
    }

    public function test_mark_failed_when_rtn_code_is_other()
    {
        $this->validate(['RtnCode' => '3000'], 'markFailed');
    }

    public function test_mark_captured_when_cvs_store_id_exists()
    {
        $this->validate(['CVSStoreID' => '1'], 'markCaptured');
    }

    public function test_mark_new()
    {
        $this->validate([], 'markNew');
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
