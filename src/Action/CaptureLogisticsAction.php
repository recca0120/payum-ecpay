<?php

namespace PayumTW\Ecpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use PayumTW\Ecpay\LogisticsApi as Api;

class CaptureLogisticsAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException(sprintf('Not supported. Expected %s instance to be set as api.', Api::class));
        }

        $this->api = $api;
    }

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
            $details->replace($this->api->parseResult($httpRequest->request));

            return;
        }

        $token = $request->getToken();
        $targetUrl = $token->getTargetUrl();
        // 無 SenderName 時則執行地圖查詢
        if (empty($details['SenderName']) === true) {
            if (empty($details['ServerReplyURL']) === true) {
                $details['ServerReplyURL'] = $targetUrl;
            }
            $params = $this->api->prepareMap($details->toUnsafeArray());

            throw new HttpPostRedirect(
                $params['apiEndpoint'],
                $params['params']
            );
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

        $payment = $this->api->preparePayment($details->toUnsafeArray());
        $details->replace($this->api->parseResult($payment));
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
