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

        if ($details['RtnCode'] === '2') {
            $request->markPending();

            return;
        }

        if ($details['RtnCode'] === '1') {
            if (isset($details['Action']) === true) {
                switch ($details['Action']) {
                    case 'C':
                        $request->markCaptured();
                        break;
                    case 'R':
                        $request->markRefunded();
                        break;
                    case 'E':
                    case 'N':
                        $request->markCanceled();
                        break;
                }

                return;
            } else {
                $request->markCaptured();

                return;
            }
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
