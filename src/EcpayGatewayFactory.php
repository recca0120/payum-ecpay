<?php

namespace PayumTW\Ecpay;

use Payum\Core\GatewayFactory;
use PayumTW\Ecpay\Action\SyncAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\CancelAction;
use PayumTW\Ecpay\Action\NotifyAction;
use PayumTW\Ecpay\Action\RefundAction;
use PayumTW\Ecpay\Action\StatusAction;
use PayumTW\Ecpay\Action\CaptureAction;
use PayumTW\Ecpay\Action\ConvertPaymentAction;
use PayumTW\Ecpay\Action\Api\CancelTransactionAction;
use PayumTW\Ecpay\Action\Api\CreateTransactionAction;
use PayumTW\Ecpay\Action\Api\RefundTransactionAction;
use PayumTW\Ecpay\Action\Api\GetTransactionDataAction;

class EcpayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'ecpay',
            'payum.factory_title' => 'Ecpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),

            'payum.action.api.create_transaction' => new CreateTransactionAction(),
            'payum.action.api.refund_transaction' => new RefundTransactionAction(),
            'payum.action.api.cancel_transaction' => new CancelTransactionAction(),
            'payum.action.api.get_transaction_data' => new GetTransactionDataAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => true,
            ];

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['MerchantID', 'HashKey', 'HashIV'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new EcpayApi((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
