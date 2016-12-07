<?php

namespace PayumTW\Ecpay\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use PayumTW\Ecpay\Request\Api\RefundTransaction;

class RefundTransactionAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @param $request RefundTransaction
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $details->replace($this->api->refundTransaction((array) $details));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof RefundTransaction &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
