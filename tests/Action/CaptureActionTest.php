<?php

namespace PayumTW\Ecpay\Tests\Action;

use Mockery as m;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetHttpRequest;
use PayumTW\Ecpay\Action\CaptureAction;

class CaptureActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExecute()
    {
        $action = new CaptureAction();
        $request = m::mock(new Capture(new ArrayObject([])));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );
        $gateway->shouldReceive('execute')->once()->with(m::on(function ($getHttpRequest) {
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
            'OrderResultURL' => $targetUrl,
            'ReturnURL' => $notifyTargetUrl,
        ], (array) $request->getModel());
    }

    public function testCaptured()
    {
        $action = new CaptureAction();
        $request = m::mock(new Capture(new ArrayObject([])));

        $action->setGateway(
            $gateway = m::mock('Payum\Core\GatewayInterface')
        );
        $response = [
            'RtnCode' => '1',
        ];
        $gateway->shouldReceive('execute')->once()->with(m::on(function ($getHttpRequest) use ($response) {
            $getHttpRequest->request = $response;

            return $getHttpRequest instanceof GetHttpRequest;
        }));

        $action->setApi(
            $api = m::mock('PayumTW\Ecpay\Api')
        );
        $api->shouldReceive('verifyHash')->once()->with($response)->andReturn(false);

        $action->execute($request);

        $this->assertSame([
            'RtnCode' => '10400002',
        ], (array) $request->getModel());
    }
}
