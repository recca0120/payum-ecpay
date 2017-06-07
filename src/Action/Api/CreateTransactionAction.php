<?php

namespace PayumTW\Ecpay\Action\Api;

use PayumTW\Ecpay\EcpayLogisticsApi;
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

        if ($this->api instanceof EcpayLogisticsApi && empty($details['GoodsAmount']) === true) {
            $params = $this->api->createCvsMapTransaction((array) $details);
            $endpoint = $this->api->getApiEndpoint();

            throw new HttpPostRedirect(
                $endpoint, $params
            );
        }

        $params = $this->api->createTransaction((array) $details);

        if (isset($params['RtnCode']) === true ||
            isset($params['ResCode']) === true ||
            isset($params['RtnMerchantTradeNo']) === true && isset($params['RtnOrderNo']) === true ||
            isset($params['CVSStoreID']) === true ||
            isset($params['ErrorMessage']) === true
        ) {
            $details->replace($params);
        } else {
            $endpoint = $this->api->getApiEndpoint();

            throw new HttpPostRedirect(
                $endpoint, $params
            );
        }
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
