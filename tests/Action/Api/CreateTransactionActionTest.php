<?php

use Mockery as m;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\Api\CreateTransactionAction;

class CreateTransactionActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_api_http_post_redirect()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $api = m::spy('PayumTW\Ecpay\Api');
        $request = m::spy('PayumTW\Ecpay\Request\Api\CreateTransaction');
        $details = new ArrayObject([
            'foo' => 'bar',
            'GoodsAmount' => 1,
        ]);
        $endpoint = 'foo.endpoint';

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $api
            ->shouldReceive('createTransaction')->andReturn((array) $details)
            ->shouldReceive('getApiEndpoint')->andReturn($endpoint);

        $action = new CreateTransactionAction();
        $action->setApi($api);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
            $this->assertSame((array) $details, $response->getFields());
        }

        $request->shouldHaveReceived('getModel')->twice();
        $api->shouldHaveReceived('createTransaction')->with((array) $details)->once();
    }

    public function test_logistics_api_http_post_redirect()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $api = m::spy('PayumTW\Ecpay\Api');
        $request = m::spy('PayumTW\Ecpay\Request\Api\CreateTransaction');
        $details = new ArrayObject([
            'foo' => 'bar',
        ]);
        $endpoint = 'foo.endpoint';

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $api
            ->shouldReceive('createCvsMapTransaction')->andReturn((array) $details)
            ->shouldReceive('getApiEndpoint')->andReturn($endpoint);

        $action = new CreateTransactionAction();
        $action->setApi($api);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
            $this->assertSame((array) $details, $response->getFields());
        }

        $request->shouldHaveReceived('getModel')->twice();
        $api->shouldHaveReceived('createCvsMapTransaction')->with((array) $details)->once();
        $api->shouldHaveReceived('getApiEndpoint')->once();
    }

    public function test_logistics_api_when_isset_rtn_code()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $api = m::spy('PayumTW\Ecpay\Api');
        $request = m::spy('PayumTW\Ecpay\Request\Api\CreateTransaction');
        $details = new ArrayObject([
            'RtnCode' => '300',
            'GoodsAmount' => 1,
        ]);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $api
            ->shouldReceive('createTransaction')->andReturn((array) $details);

        $action = new CreateTransactionAction();
        $action->setApi($api);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
        }

        $request->shouldHaveReceived('getModel')->twice();
        $api->shouldHaveReceived('createTransaction')->with((array) $details)->once();
    }
}
