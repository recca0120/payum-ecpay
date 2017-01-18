<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\CaptureLogisticsAction;

class CaptureLogisticsActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_execute_when_goods_amount_is_zero()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $gateway = m::spy('Payum\Core\GatewayInterface');
        $tokenFactory = m::spy('Payum\Core\Security\GenericTokenFactoryInterface');
        $request = m::spy('Payum\Core\Request\Capture');
        $details = new ArrayObject([
            'GoodsAmount' => 0,
        ]);
        $token = m::spy('Payum\Core\Security\TokenInterface');
        $targetUrl = 'foo.target_url';

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details)
            ->shouldReceive('getToken')->andReturn($token);

        $token
            ->shouldReceive('getTargetUrl')->andReturn($targetUrl);

        $action = new CaptureLogisticsAction();
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
    }

    public function test_execute_when_goods_amount_bigger_than_zero()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $gateway = m::spy('Payum\Core\GatewayInterface');
        $tokenFactory = m::spy('Payum\Core\Security\GenericTokenFactoryInterface');
        $request = m::spy('Payum\Core\Request\Capture');
        $details = new ArrayObject([
            'GoodsAmount' => 1,
        ]);
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

        $action = new CaptureLogisticsAction();
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

    public function test_execute_when_csv_store_id_exists()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $gateway = m::spy('Payum\Core\GatewayInterface');
        $tokenFactory = m::spy('Payum\Core\Security\GenericTokenFactoryInterface');
        $request = m::spy('Payum\Core\Request\Capture');
        $details = new ArrayObject(['CVSStoreID' => '1']);
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

        $action = new CaptureLogisticsAction();
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
    }
}
