<?php

namespace PayumTW\Ecpay\Tests\Action;

use Mockery as m;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetHttpRequest;
use PayumTW\Ecpay\Action\CaptureLogisticsAction;

class CaptureLogisticsActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExecute()
    {
        $action = new CaptureLogisticsAction();
        $request = m::mock(new Capture(new ArrayObject([
            'GoodsAmount' => 100,
        ])));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );

        $gateway->shouldReceive('execute')->once()->with(m::on(function($getHttpRequest) {
            return $getHttpRequest instanceof GetHttpRequest;
        }));

        $request->shouldReceive('getToken')->once()->andReturn(
            $token = m::mock('Payum\Core\Security\TokenInterface')
        );
        $token->shouldReceive('getTargetUrl')->once()->andReturn($targetUrl = 'targetUrl');

        $action->setGenericTokenFactory(
            $tokenFactory = m::mock('Payum\Core\Security\GenericTokenFactoryInterface')
        );
        $token->shouldReceive('getGatewayName')->once()->andReturn(
            $gatewayName = 'gatewayName'
        );
        $token->shouldReceive('getDetails')->once()->andReturn(
            $details = ['foo' => 'bar']
        );
        $tokenFactory->shouldReceive('createNotifyToken')->once()->with($gatewayName, $details)->andReturn(
            $notifyToken = m::mock('Payum\Core\Security\TokenInterface')
        );
        $notifyToken->shouldReceive('getTargetUrl')->once()->andReturn($notifyTargetUrl = 'notifyTargetUrl');
        $gateway->shouldReceive('execute')->once()->with(m::type('PayumTW\Ecpay\Request\Api\CreateTransaction'));

        $action->execute($request);

        $this->assertSame([
            'GoodsAmount' => 100,
            'ServerReplyURL' => $notifyTargetUrl,
            'LogisticsC2CReplyURL' => $notifyTargetUrl,
            'ClientReplyURL' => $targetUrl,
        ], (array) $request->getModel());
    }

    public function testExecuteCVS()
    {
        $action = new CaptureLogisticsAction();
        $request = m::mock(new Capture(new ArrayObject([
            'GoodsAmount' => 0,
        ])));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );

        $gateway->shouldReceive('execute')->once()->with(m::on(function($getHttpRequest) {
            return $getHttpRequest instanceof GetHttpRequest;
        }));

        $request->shouldReceive('getToken')->once()->andReturn(
            $token = m::mock('Payum\Core\Security\TokenInterface')
        );
        $token->shouldReceive('getTargetUrl')->once()->andReturn($targetUrl = 'targetUrl');

        $gateway->shouldReceive('execute')->once()->with(m::type('PayumTW\Ecpay\Request\Api\CreateTransaction'));

        $action->execute($request);

        $this->assertSame([
            'GoodsAmount' => 0,
            'ServerReplyURL' => $targetUrl,
        ], (array) $request->getModel());
    }

    public function testCapturedCVS()
    {
        $action = new CaptureLogisticsAction();
        $request = m::mock(new Capture(new ArrayObject([])));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );
        $response = [
            'CVSStoreID' => 'foo',
        ];
        $gateway->shouldReceive('execute')->once()->with(m::on(function($getHttpRequest) use ($response) {
            $getHttpRequest->request = $response;

            return $getHttpRequest instanceof GetHttpRequest;
        }));

        $action->execute($request);

        $this->assertSame($response, (array) $request->getModel());
    }
}
