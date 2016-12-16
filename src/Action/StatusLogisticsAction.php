<?php

namespace PayumTW\Ecpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusLogisticsAction implements ActionInterface
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

        if (empty($details['ErrorMessage']) === false) {
            $request->markFailed();

            return;
        }

        if (isset($details['RtnCode']) === true) {
            if ($details['RtnCode'] === '0') {
                $request->markFailed();

                return;
            }

            if ($details['RtnCode'] === '1') {
                if (isset($details['RtnMerchantTradeNo']) === true) {
                    $request->markRefunded();

                    return;
                }

                $request->markCaptured();

                return;
            }
        }

        if (isset($details['ResCode']) === true) {
            if ($details['ResCode'] === '0') {
                $request->markFailed();
            } else {
                $request->markCaptured();
            }

            return;
        }

        if (isset($details['CVSStoreID']) === true) {
            $request->markCaptured();

            return;
        }

        $request->markUnknown();
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
