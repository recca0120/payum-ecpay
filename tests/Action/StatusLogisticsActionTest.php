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

    public function test_request_mark_new()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new StatusLogisticsAction();
        $request = m::mock('Payum\Core\Request\GetStatusInterface');
        $model = new ArrayObject();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($model)->twice()
            ->shouldReceive('markNew')->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->execute($request);
    }

    public function test_request_mark_captured()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new StatusLogisticsAction();
        $request = m::mock('Payum\Core\Request\GetStatusInterface');
        $model = new ArrayObject([
            'RtnCode' => '300',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($model)->twice()
            ->shouldReceive('markCaptured')->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->execute($request);
    }

    public function test_request_mark_failed()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new StatusLogisticsAction();
        $request = m::mock('Payum\Core\Request\GetStatusInterface');
        $model = new ArrayObject([
            'RtnCode' => '-1',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($model)->twice()
            ->shouldReceive('markFailed')->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->execute($request);
    }

    public function test_request_store_mark_captured()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new StatusLogisticsAction();
        $request = m::mock('Payum\Core\Request\GetStatusInterface');
        $model = new ArrayObject([
            'CVSStoreID' => '1',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($model)->twice()
            ->shouldReceive('markCaptured')->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->execute($request);
    }
}
