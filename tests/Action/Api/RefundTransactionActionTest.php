<?php

namespace PayumTW\Ecpay\Tests\Action\Api;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Request\Api\RefundTransaction;
use PayumTW\Ecpay\Action\Api\RefundTransactionAction;

class RefundTransactionActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testGetTransactionData()
    {
        $action = new RefundTransactionAction();
        $request = new RefundTransaction(new ArrayObject([]));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('refundTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['RepCode' => '1']);

        $action->execute($request);

        $this->assertSame($params, (array) $request->getModel());
    }
}
