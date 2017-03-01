<?php

namespace PayumTW\Ecpay\Tests\Action;

use Mockery as m;
use Payum\Core\Request\Notify;
use PHPUnit\Framework\TestCase;
use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetHttpRequest;
use PayumTW\Ecpay\Action\NotifyAction;

class NotifyActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExecute()
    {
        $action = new NotifyAction();
        $request = new Notify(new ArrayObject([]));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );

        $params = [
            'foo' => 'bar',
        ];

        $gateway->shouldReceive('execute')->once()->with(m::on(function ($httpRequest) use ($params) {
            $httpRequest->request = $params;

            return $httpRequest instanceof GetHttpRequest;
        }));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('verifyHash')->once()->with($params)->andReturn(true);

        try {
            $action->execute($request);
        } catch (ReplyInterface $e) {
            $this->assertSame(200, $e->getStatusCode());
            $this->assertSame('1|OK', $e->getContent());
        }

        $this->assertSame($params, (array) $request->getModel());
    }

    public function testExecuteFail()
    {
        $action = new NotifyAction();
        $request = new Notify(new ArrayObject([]));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );

        $params = [
            'foo' => 'bar',
        ];

        $gateway->shouldReceive('execute')->once()->with(m::on(function ($httpRequest) use ($params) {
            $httpRequest->request = $params;

            return $httpRequest instanceof GetHttpRequest;
        }));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );

        $api->shouldReceive('verifyHash')->once()->with($params)->andReturn(false);

        try {
            $action->execute($request);
        } catch (ReplyInterface $e) {
            $this->assertSame(400, $e->getStatusCode());
            $this->assertSame('0|CheckMacValue verify fail.', $e->getContent());
        }
    }
}
