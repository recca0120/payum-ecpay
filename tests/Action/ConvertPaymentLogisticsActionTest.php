<?php

namespace PayumTW\Ecpay\Tests\Action;

use Mockery as m;
use Payum\Core\Request\Convert;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\ConvertPaymentLogisticsAction;

class ConvertPaymentLogisticsActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testExecute()
    {
        $action = new ConvertPaymentLogisticsAction();
        $request = new Convert(
            $payment = m::mock('Payum\Core\Model\PaymentInterface'),
            $to = 'array'
        );
        $payment->shouldReceive('getDetails')->once()->andReturn([]);
        $payment->shouldReceive('getNumber')->once()->andReturn($number = 'foo');
        $payment->shouldReceive('getClientEmail')->once()->andReturn($clientEmail = 'foo');
        $payment->shouldReceive('getTotalAmount')->once()->andReturn($totalAmount = 'foo');
        $payment->shouldReceive('getDescription')->once()->andReturn($description = 'foo');

        $action->execute($request);
        $this->assertSame([
            'MerchantTradeNo' => $number,
            'ReceiverEmail' => $clientEmail,
            'GoodsAmount' => $totalAmount,
            'TradeDesc' => $description,
            'AllPayLogisticsID' => $number,
        ], $request->getResult());
    }
}
