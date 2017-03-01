<?php

namespace PayumTW\Ecpay\Tests\Action\Api;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Request\Api\CreateTransaction;
use PayumTW\Ecpay\Action\Api\CreateTransactionAction;

class CreateTransactionActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExecute()
    {
        $action = new CreateTransactionAction();
        $request = new CreateTransaction(new ArrayObject([
            'GoodsAmount' => 100,
        ]));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('createTransaction')->once()->with((array) $request->getModel())->andReturn($result = ['foo' => 'bar']);
        $api->shouldReceive('getApiEndpoint')->once()->andReturn($apiEndpoint = 'foo');

        try {
            $action->execute($request);
        } catch (HttpResponse $e) {
            $this->assertSame($apiEndpoint, $e->getUrl());
            $this->assertSame($result, $e->getFields());
        }
    }

    public function testCaptured()
    {
        $action = new CreateTransactionAction();
        $request = new CreateTransaction(new ArrayObject([
            'GoodsAmount' => 100,
        ]));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('createTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['RtnCode' => 'foo']);
        $action->execute($request);
        $this->assertSame(array_merge((array) $request->getModel(), $params), (array) $request->getModel());

        $api->shouldReceive('createTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['ResCode' => 'foo']);
        $action->execute($request);
        $this->assertSame(array_merge((array) $request->getModel(), $params), (array) $request->getModel());

        $api->shouldReceive('createTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['RtnMerchantTradeNo' => 'foo', 'RtnOrderNo' => 'foo']);
        $action->execute($request);
        $this->assertSame(array_merge((array) $request->getModel(), $params), (array) $request->getModel());

        $api->shouldReceive('createTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['CVSStoreID' => 'foo']);
        $action->execute($request);
        $this->assertSame(array_merge((array) $request->getModel(), $params), (array) $request->getModel());

        $api->shouldReceive('createTransaction')->once()->with((array) $request->getModel())->andReturn($params = ['ErrorMessage' => 'foo']);
        $action->execute($request);
        $this->assertSame(array_merge((array) $request->getModel(), $params), (array) $request->getModel());
    }

    public function testExecuteCVS()
    {
        $action = new CreateTransactionAction();
        $request = new CreateTransaction(new ArrayObject([
            'GoodsAmount' => 0,
        ]));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('createCvsMapTransaction')->once()->with((array) $request->getModel())->andReturn($result = ['foo' => 'bar']);
        $api->shouldReceive('getApiEndpoint')->once()->andReturn($apiEndpoint = 'foo');

        try {
            $action->execute($request);
        } catch (HttpResponse $e) {
            $this->assertSame($apiEndpoint, $e->getUrl());
            $this->assertSame($result, $e->getFields());
        }
    }
}
