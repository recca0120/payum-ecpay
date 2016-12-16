<?php

namespace PayumTW\Ecpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (isset($details['RtnCode']) === false) {
            $request->markNew();

            return;
        }

        $isSuccess = in_array($details['RtnCode'], ['1', '3']);

        if ($isSuccess === true && isset($details['Action']) === true) {
            switch ($details['Action']) {
                case 'R':
                    $request->markRefunded();
                    break;
                case 'E':
                case 'N':
                    $request->markCanceled();
                    break;
            }

            return;
        } elseif ($isSuccess === true) {
            $request->markCaptured();

            return;
        }

        $request->markFailed();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
