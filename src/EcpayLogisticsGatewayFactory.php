<?php

namespace PayumTW\Ecpay;

use Payum\Core\GatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\StatusLogisticsAction;
use PayumTW\Ecpay\Action\CaptureLogisticsAction;
use PayumTW\Ecpay\Action\ConvertPaymentLogisticsAction;
use PayumTW\Ecpay\Action\Api\CreateTransactionAction;
use PayumTW\Ecpay\Action\Api\GetTransactionDataAction;
use PayumTW\Ecpay\Action\SyncAction;

class EcpayLogisticsGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'ecpay_logistics',
            'payum.factory_title' => 'Ecpay Logistics',
            'payum.action.capture' => new CaptureLogisticsAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.status' => new StatusLogisticsAction(),
            'payum.action.convert_payment' => new ConvertPaymentLogisticsAction(),

            'payum.action.api.create_transaction' => new CreateTransactionAction(),
            'payum.action.api.get_transaction_data' => new GetTransactionDataAction(),
        ]);

        /*
         * 會員編號(MerchantID)    2000132(B2C)(Home) 2000933(C2C)
         * 登入廠商後台帳號/密碼     StageTest/test1234 LogisticsC2CTest/test1234
         * 廠商後台測試環境         https://vendor-stage.ecpay.com.tw，此網站提供查詢測試的物流訂單相關資訊，也可執行物流訂單建立的功能。
         * 物流介接的 HashKey      5294y06JbISpM5x9 XBERn1YOvpM9nfZc
         * 物流介接的 HashIV       v77hoKGq4kWxNNIS h1ONHk4P4yqbl5LK
         */

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

                return new LogisticsApi((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
