<?php

use Mockery as m;
use PayumTW\Ecpay\Action\SyncAction;
use Payum\Core\Bridge\Spl\ArrayObject;

class SyncActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_execute()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new SyncAction();
        $gateway = m::mock('Payum\Core\GatewayInterface');
        $request = m::mock('Payum\Core\Request\Sync');
        $details = new ArrayObject();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request->shouldReceive('getModel')->andReturn($details)->twice();

        $gateway
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))
            ->shouldReceive('execute')->with(m::type('PayumTW\Ecpay\Request\Api\GetTransactionData'));

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->execute($request);
    }
}
