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

    public function test_redirect_to_allpay()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new CaptureAction();
        $gateway = m::mock('Payum\Core\GatewayInterface');
        $request = m::mock('Payum\Core\Request\Capture');
        $tokenFactory = m::mock('Payum\Core\Security\GenericTokenFactoryInterface');
        $token = m::mock('stdClass');
        $notifyToken = m::mock('stdClass');
        $api = m::mock('PayumTW\Ecpay\Api');
        $details = new ArrayObject([]);
        $getHttpRequest = m::mock('Payum\Core\Request\GetHttpRequest');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->once()->andReturnUsing(function () use ($getHttpRequest) {
                return $getHttpRequest;
            })
            ->shouldReceive('execute')->with(m::type('PayumTW\Ecpay\Request\Api\CreateTransaction'))->once();

        $request
            ->shouldReceive('getModel')->twice()->andReturn($details)
            ->shouldReceive('getToken')->once()->andReturn($token);

        $token
            ->shouldReceive('getTargetUrl')->andReturn('fooOrderResultURL')
            ->shouldReceive('getGatewayName')->andReturn('fooGatewayName')
            ->shouldReceive('getDetails')->andReturn([
                'foo' => 'bar',
            ]);

        $notifyToken->shouldReceive('getTargetUrl')->andReturn('fooReturnURL');

        $tokenFactory
            ->shouldReceive('createNotifyToken')->once()->andReturn($notifyToken);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setGenericTokenFactory($tokenFactory);
        $action->execute($request);
        $this->assertSame([
            'OrderResultURL' => 'fooOrderResultURL',
            'ReturnURL'      => 'fooReturnURL',
        ], (array) $details);
    }

    public function test_allpay_response()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new CaptureAction();
        $gateway = m::mock('Payum\Core\GatewayInterface');
        $request = m::mock('Payum\Core\Request\Capture');
        $tokenFactory = m::mock('Payum\Core\Security\GenericTokenFactoryInterface');
        $token = m::mock('stdClass');
        $notifyToken = m::mock('stdClass');
        $api = m::mock('PayumTW\Ecpay\Api');
        $details = new ArrayObject([]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $expected = [
            'RtnCode' => '1',
        ];

        $gateway
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->once()->andReturnUsing(function ($httpRequest) use ($api, $expected) {
                $httpRequest->request = $expected;
            })
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\Sync'))->once();

        $request
            ->shouldReceive('getModel')->twice()->andReturn($details);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setGenericTokenFactory($tokenFactory);
        $action->execute($request);
    }
}
