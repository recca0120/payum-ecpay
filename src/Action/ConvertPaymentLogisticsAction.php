<?php

namespace PayumTW\Ecpay\Action;

use Payum\Core\Request\Convert;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Exception\RequestNotSupportedException;

class ConvertPaymentLogisticsAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details['MerchantTradeNo'] = strtoupper($payment->getNumber());
        $details['ReceiverEmail'] = $payment->getClientEmail();
        $details['GoodsAmount'] = (int) $payment->getTotalAmount();
        $details['TradeDesc'] = $payment->getDescription();
        $request->setResult((array) $details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array';
    }
}
