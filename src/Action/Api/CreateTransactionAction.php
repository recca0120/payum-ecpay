<?php

namespace PayumTW\Ecpay\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpPostRedirect;
use PayumTW\Ecpay\Request\Api\CreateTransaction;
use Payum\Core\Exception\RequestNotSupportedException;

class CreateTransactionAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @param $request CreateTransaction
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        throw new HttpPostRedirect(
            $this->api->getApiEndpoint(),
            $this->api->createTransaction((array) $details)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateTransaction &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
