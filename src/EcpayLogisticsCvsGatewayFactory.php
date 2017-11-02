<?php

namespace PayumTW\Ecpay;

use Payum\Core\Bridge\Spl\ArrayObject;

class EcpayLogisticsCvsGatewayFactory extends EcpayLogisticsGatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        parent::populateConfig($config);
        $config->defaults([
            'payum.factory_name' => 'ecpay_logistics_cvs',
            'payum.factory_title' => 'Ecpay Logistics CVS',
        ]);
    }
}
