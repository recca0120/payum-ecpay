<?php

namespace PayumTW\Ecpay;

use Http\Message\MessageFactory;
use Payum\Core\HttpClientInterface;
use PayumTW\Ecpay\Bridge\Ecpay\AllInOne;
use PayumTW\Ecpay\Bridge\Ecpay\DeviceType;
use PayumTW\Ecpay\Bridge\Ecpay\InvoiceState;

class Api extends BaseApi
{
    /**
     * $client.
     *
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * MessageFactory.
     *
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * $options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * $api.
     *
     * @var \PayumTW\Ecpay\Bridge\Ecpay\AllInOne
     */
    protected $api;

    /**
     * @param array               $options
     * @param HttpClientInterface $client
     * @param MessageFactory      $messageFactory
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory, AllInOne $api = null)
    {
        $this->options = $options;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
        $this->api = is_null($api) === true ? new AllInOne() : $api;
        $this->api->HashKey = $this->options['HashKey'];
        $this->api->HashIV = $this->options['HashIV'];
        $this->api->MerchantID = $this->options['MerchantID'];
    }

    /**
     * getApiEndpoint.
     *
     * @return string
     */
    public function getApiEndpoint($name = 'AioCheckOut')
    {
        $map = [

            'AioCheckOut'                    => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V2 ',
            'QueryTradeInfo'                 => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V2',
            'QueryPeriodCreditCardTradeInfo' => 'https://payment.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo',
            'DoAction'                       => 'https://payment.ecpay.com.tw/CreditDetail/DoAction',
            'AioChargeback'                  => 'https://payment.ecpay.com.tw/Cashier/AioChargeback',
        ];

        if ($this->options['sandbox'] === true) {
            $map = [
                'AioCheckOut'                    => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2',
                'QueryTradeInfo'                 => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V2',
                'QueryPeriodCreditCardTradeInfo' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo',
                'DoAction'                       => null,
                'AioChargeback'                  => 'https://payment-stage.ecpay.com.tw/Cashier/AioChargeback',
            ];
        }

        return $map[$name];
    }

    /**
     * createTransaction.
     *
     * @param array $params
     * @param mixed $request
     *
     * @return array
     */
    public function createTransaction(array $params)
    {
        $this->api->ServiceURL = $this->getApiEndpoint('AioCheckOut');
        $this->api->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
        $this->api->Send['DeviceSource'] = $this->isMobile() ? DeviceType::Mobile : DeviceType::PC;
        $this->api->Send = array_replace(
            $this->api->Send,
            array_intersect_key($params, $this->api->Send)
        );

        // 電子發票參數
        /*
        $api->Send['InvoiceMark'] = InvoiceState::Yes;
        $api->SendExtend['RelateNumber'] = $MerchantTradeNo;
        $api->SendExtend['CustomerEmail'] = 'test@allpay.com.tw';
        $api->SendExtend['CustomerPhone'] = '0911222333';
        $api->SendExtend['TaxType'] = TaxType::Dutiable;
        $api->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
        $api->SendExtend['InvoiceItems'] = array();
        // 將商品加入電子發票商品列表陣列
        foreach ($api->Send['Items'] as $info)
        {
            array_push($api->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => TaxType::Dutiable));
        }
        $api->SendExtend['InvoiceRemark'] = '測試發票備註';
        $api->SendExtend['DelayDay'] = '0';
        $api->SendExtend['InvType'] = InvType::General;
        */

        return $this->api->CheckOut();
    }

    /**
     * cancelTransaction.
     *
     * @param array $params
     *
     * @return array
     */
    public function cancelTransaction($params)
    {
        $this->api->ServiceURL = $this->getApiEndpoint('DoAction');
        $this->api->Action = array_replace(
            $this->api->Action,
            array_intersect_key($params, $this->api->Action)
        );

        return $this->api->DoAction();
    }

    /**
     * refundTransaction.
     *
     * @param array $params
     *
     * @return array
     */
    public function refundTransaction($params)
    {
        $this->api->ServiceURL = $this->getApiEndpoint('AioChargeback');
        $this->api->ChargeBack = array_replace(
            $this->api->ChargeBack,
            array_intersect_key($params, $this->api->ChargeBack)
        );

        return $this->api->AioChargeback();
    }

    /**
     * getTransactionData.
     *
     * @param mixed $params
     *
     * @return array
     */
    public function getTransactionData($params)
    {
        $details = [];
        if (empty($params['response']) === false) {
            if ($this->verifyHash($params) === false) {
                $details['RtnCode'] = '10400002';
            } else {
                $details = $params['response'];
            }
        } else {
            $this->api->ServiceURL = $this->getApiEndpoint('QueryTradeInfo');
            $this->api->Query['MerchantTradeNo'] = $params['MerchantTradeNo'];
            $details = $this->api->QueryTradeInfo();
            if (empty($details['RtnCode']) === true) {
                $details['RtnCode'] = '1';
            }
        }

        return $details;
    }
}
