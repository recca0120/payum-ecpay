<?php

namespace PayumTW\Ecpay\Tests\Action\Api;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Request\Api\CancelTransaction;
use PayumTW\Ecpay\Action\Api\CancelTransactionAction;

class CancelTransactionActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExecute()
    {
        $action = new CancelTransactionAction();
        $request = new CancelTransaction(new ArrayObject([]));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('cancelTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['RepCode' => '1']);

        $action->execute($request);

        $this->assertSame($params, (array) $request->getModel());
    }
}
