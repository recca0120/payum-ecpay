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

    public function test_sync()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\Sync');
        $gateway = m::spy('Payum\Core\GatewayInterface');
        $details = new ArrayObject([]);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $action = new SyncAction();
        $action->setGateway($gateway);
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getModel')->twice();
        $gateway->shouldHaveReceived('execute')->with(m::type('PayumTW\Ecpay\Request\Api\GetTransactionData'))->once();
    }
}
