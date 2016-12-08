<?php

use Mockery as m;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\CaptureLogisticsAction;

class CaptureLogisticsActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_redirect_csv_map()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new CaptureLogisticsAction();
        $gateway = m::mock('Payum\Core\GatewayInterface');
        $request = m::mock('Payum\Core\Request\Capture');
        $tokenFactory = m::mock('Payum\Core\Security\GenericTokenFactoryInterface');
        $token = m::mock('stdClass');
        $notifyToken = m::mock('stdClass');
        $api = m::mock('PayumTW\Ecpay\LogisticsApi');
        $model = new ArrayObject([]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway->shouldReceive('execute')->with('Payum\Core\Request\GetHttpRequest')->once();

        $request
            ->shouldReceive('getModel')->twice()->andReturn($model)
            ->shouldReceive('getToken')->once()->andReturn($token);

        $token
            ->shouldReceive('getTargetUrl')->andReturn('fooTragetURL')
            ->shouldReceive('getGatewayName')->andReturn('fooGatewayName')
            ->shouldReceive('getDetails')->andReturn([
                'foo' => 'bar',
            ]);

        $api->shouldReceive('prepareMap')->once()->andReturn([
            'apiEndpoint' => 'fooApiEndpoint',
            'params'      => [
                'foo' => 'bar',
            ],
        ]);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setApi($api);
        $action->setGenericTokenFactory($tokenFactory);
        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
        }
    }

    public function test_receive_csv_map()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new CaptureLogisticsAction();
        $gateway = m::mock('Payum\Core\GatewayInterface');
        $request = m::mock('Payum\Core\Request\Capture');
        $tokenFactory = m::mock('Payum\Core\Security\GenericTokenFactoryInterface');
        $token = m::mock('stdClass');
        $notifyToken = m::mock('stdClass');
        $api = m::mock('PayumTW\Ecpay\LogisticsApi');
        $model = new ArrayObject([]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway->shouldReceive('execute')->with('Payum\Core\Request\GetHttpRequest')->once()->andReturnUsing(function ($request) {
            $request->request = [
                'CVSStoreID' => 'fooCVSStoreID',
            ];

            return $request;
        });

        $request->shouldReceive('getModel')->twice()->andReturn($model);

        $api->shouldReceive('parseResult')->once()->andReturn($model);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setApi($api);
        $action->setGenericTokenFactory($tokenFactory);
        $action->execute($request);
    }

    public function test_request_logistics()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new CaptureLogisticsAction();
        $gateway = m::mock('Payum\Core\GatewayInterface');
        $request = m::mock('Payum\Core\Request\Capture');
        $tokenFactory = m::mock('Payum\Core\Security\GenericTokenFactoryInterface');
        $token = m::mock('stdClass');
        $notifyToken = m::mock('stdClass');
        $api = m::mock('PayumTW\Ecpay\LogisticsApi');
        $model = new ArrayObject([
            'SenderName' => 'fooSenderName',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway->shouldReceive('execute')->with('Payum\Core\Request\GetHttpRequest')->once()->andReturnUsing(function ($request) {
            $request->request = [];

            return $request;
        });

        $request
            ->shouldReceive('getModel')->twice()->andReturn($model)
            ->shouldReceive('getToken')->once()->andReturn($token);

        $token
            ->shouldReceive('getTargetUrl')->once()->andReturn('fooTargetUrl')
            ->shouldReceive('getGatewayName')->once()->andReturn('foogGatewayName')
            ->shouldReceive('getDetails')->once()->andReturn([]);

        $tokenFactory->shouldReceive('createNotifyToken')->once()->andReturn($notifyToken);

        $notifyToken->shouldReceive('getTargetUrl')->once()->andReturn('fooNotifyTargetUrl');

        $api
            ->shouldReceive('preparePayment')->once()->andReturn($model)
            ->shouldReceive('parseResult')->once()->andReturn($model);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setApi($api);
        $action->setGenericTokenFactory($tokenFactory);
        $action->execute($request);
    }

    /**
     * @expectedException Payum\Core\Exception\UnsupportedApiException
     */
    public function test_api_fail()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new CaptureLogisticsAction();
        $api = m::mock('stdClass');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setApi($api);
    }
}
