<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\CaptureAction;

class CaptureActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_execute_get_transcation()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $gateway = m::spy('Payum\Core\GatewayInterface');
        $tokenFactory = m::spy('Payum\Core\Security\GenericTokenFactoryInterface');
        $request = m::spy('Payum\Core\Request\Capture');
        $details = new ArrayObject([]);
        $token = m::spy('Payum\Core\Security\TokenInterface');
        $targetUrl = 'foo.target_url';
        $gatewayName = 'foo.gateway';
        $notifyToken = m::spy('Payum\Core\Security\TokenInterface');

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details)
            ->shouldReceive('getToken')->andReturn($token);

        $token
            ->shouldReceive('getTargetUrl')->andReturn($targetUrl)
            ->shouldReceive('getGatewayName')->andReturn($gatewayName)
            ->shouldReceive('getDetails')->andReturn($details);

        $tokenFactory
            ->shouldReceive('createNotifyToken')->andReturn($notifyToken);

        $notifyToken
            ->shouldReceive('getTargetUrl')->andReturn($targetUrl);

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $action->setGenericTokenFactory($tokenFactory);
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getModel')->twice();
        $gateway->shouldHaveReceived('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->once();
        $request->shouldHaveReceived('getToken')->once();
        $token->shouldHaveReceived('getTargetUrl')->once();
        $token->shouldHaveReceived('getGatewayName')->once();
        $token->shouldHaveReceived('getDetails')->once();
        $tokenFactory->shouldHaveReceived('createNotifyToken')->with($gatewayName, $details)->once();
        $notifyToken->shouldHaveReceived('getTargetUrl')->once();
        $gateway->shouldHaveReceived('execute')->with(m::type('PayumTW\Ecpay\Request\Api\CreateTransaction'))->once();
    }

    public function test_execute_when_rtn_code_exists()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $gateway = m::spy('Payum\Core\GatewayInterface');
        $tokenFactory = m::spy('Payum\Core\Security\GenericTokenFactoryInterface');
        $request = m::spy('Payum\Core\Request\Capture');
        $details = new ArrayObject(['RtnCode' => '1']);
        $token = m::spy('Payum\Core\Security\TokenInterface');
        $targetUrl = 'foo.target_url';
        $gatewayName = 'foo.gateway';
        $notifyToken = m::spy('Payum\Core\Security\TokenInterface');

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details)
            ->shouldReceive('getToken')->andReturn($token);

        $gateway
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->andReturnUsing(function ($GetHttpRequest) use ($details) {
                $GetHttpRequest->request = (array) $details;

                return $GetHttpRequest;
            });

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $action->setGenericTokenFactory($tokenFactory);
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getModel')->twice();
        $gateway->shouldHaveReceived('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->once();
        $gateway->shouldHaveReceived('execute')->with(m::type('Payum\Core\Request\Sync'))->once();
    }
}
