<?php

namespace PayumTW\Ecpay\Action;

use Payum\Core\Request\Sync;
use Payum\Core\Request\Capture;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetHttpRequest;
use PayumTW\Ecpay\Request\Api\CreateTransaction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;

class CaptureLogisticsAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);
        $details = ArrayObject::ensureArrayObject($request->getModel());

        $httpRequest = new GetHttpRequest();
        $this->gateway->execute($httpRequest);

        if (isset($httpRequest->request['CVSStoreID']) === true) {
            $this->gateway->execute(new Sync($details));

            return;
        }

        $token = $request->getToken();
        $targetUrl = $token->getTargetUrl();

        if ($details['GoodsAmount'] === 0) {
            if (empty($details['ServerReplyURL']) === true) {
                $details['ServerReplyURL'] = $targetUrl;
            }

            $this->gateway->execute(new CreateTransaction($details));

            return;
        }

        if (empty($details['ServerReplyURL']) === true) {
            $notifyToken = $this->tokenFactory->createNotifyToken(
                $token->getGatewayName(),
                $token->getDetails()
            );

            $details['ServerReplyURL'] = $details['LogisticsC2CReplyURL'] = $notifyToken->getTargetUrl();
        }

        if (empty($details['ClientReplyURL']) === true) {
            $details['ClientReplyURL'] = $targetUrl;
        }

        $this->gateway->execute(new CreateTransaction($details));
        $this->gateway->execute(new Sync($details));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
