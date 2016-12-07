<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\Api\RefundTransactionAction;

class RefundTransactionActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_execute()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('PayumTW\Ecpay\Request\Api\RefundTransaction, ArrayAccess');
        $api = m::spy('PayumTW\Ecpay\Api');
        $input = [
            'cust_order_no' => 'foo.cust_order_no',
            'order_amount'  => 'foo.order_amount',
            'refund_amount' => 'foo.refund_amount',
        ];
        $details = new ArrayObject($input);

        $endpoint = 'foo.endpoint';
        $data = ['foo.data'];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $api
            ->shouldReceive('refundTransaction')->andReturn($details);

        $action = new RefundTransactionAction();
        $action->setApi($api);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $action->execute($request);
        $request->shouldHaveReceived('getModel')->twice();
        $api->shouldHaveReceived('refundTransaction')->with($input)->once();
    }
}
