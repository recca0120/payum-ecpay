<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\ConvertPaymentAction;

class ConvertPaymentActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_convert()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new ConvertPaymentAction();
        $request = m::mock('Payum\Core\Request\Convert');
        $payment = m::mock('Payum\Core\Model\PaymentInterface');
        $model = new ArrayObject();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getSource')->twice()->andReturn($payment)
            ->shouldReceive('getTo')->once()->andReturn('array');

        $payment
            ->shouldReceive('getDetails')->once()->andReturn([])
            ->shouldReceive('getNumber')->once()->andReturn('fooNumber')
            ->shouldReceive('getTotalAmount')->once()->andReturn('fooTotalAmount')
            ->shouldReceive('getDescription')->once()->andReturn('fooDescription');
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $request->shouldReceive('setResult')->once()->andReturnUsing(function ($data) {
            $this->assertSame([
                'MerchantTradeNo' => strtoupper('fooNumber'),
                'TotalAmount'     => 'fooTotalAmount',
                'TradeDesc'       => 'fooDescription',
            ], $data);
        });

        $action->execute($request);
    }
}
