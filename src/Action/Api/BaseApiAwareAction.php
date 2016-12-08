<?php

namespace PayumTW\Ecpay\Action\Api;

use PayumTW\Ecpay\Api;
use PayumTW\Ecpay\LogisticsApi;

use Payum\Core\ApiAwareInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\UnsupportedApiException;

abstract class BaseApiAwareAction implements ActionInterface, ApiAwareInterface
{
    /**
     * @var \Payum\Ecpay\Api
     */
    protected $api;

    /**
     * {@inheritdoc}
     */
    public function setApi($api)
    {
        if (true == $api instanceof Api || true == $api instanceof LogisticsApi) {
            $this->api = $api;
        } else {
            throw new UnsupportedApiException('Not supported.');
        }
    }
}
