<?php

namespace PayumTW\Ecpay;

use Http\Message\MessageFactory;
use Payum\Core\HttpClientInterface;
use PayumTW\Ecpay\Bridge\Ecpay\AllInOne;
use PayumTW\Ecpay\Bridge\Ecpay\DeviceType;
use PayumTW\Ecpay\Bridge\Ecpay\InvoiceState;

class EcpayApi extends Api
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
     * $sdk.
     *
     * @var \PayumTW\Ecpay\Bridge\Ecpay\AllInOne
     */
    protected $sdk;

    /**
     * @param array $options
     * @param HttpClientInterface $client
     * @param MessageFactory $messageFactory
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory, AllInOne $sdk = null)
    {
        $this->options = $options;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
        $this->sdk = $sdk ?: new AllInOne();
        $this->sdk->HashKey = $this->options['HashKey'];
        $this->sdk->HashIV = $this->options['HashIV'];
        $this->sdk->MerchantID = $this->options['MerchantID'];
    }

    /**
     * getApiEndpoint.
     *
     * @return string
     */
    public function getApiEndpoint($name = 'AioCheckOut')
    {
        $map = [
            'AioCheckOut' => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V2 ',
            'QueryTradeInfo' => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V2',
            'QueryPeriodCreditCardTradeInfo' => 'https://payment.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo',
            'DoAction' => 'https://payment.ecpay.com.tw/CreditDetail/DoAction',
            'AioChargeback' => 'https://payment.ecpay.com.tw/Cashier/AioChargeback',
        ];

        if ($this->options['sandbox'] === true) {
            $map = [
                'AioCheckOut' => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2',
                'QueryTradeInfo' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V2',
                'QueryPeriodCreditCardTradeInfo' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo',
                'DoAction' => null,
                'AioChargeback' => 'https://payment-stage.ecpay.com.tw/Cashier/AioChargeback',
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
        $this->sdk->ServiceURL = $this->getApiEndpoint('AioCheckOut');
        $this->sdk->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
        $this->sdk->Send['DeviceSource'] = $this->isMobile() ? DeviceType::Mobile : DeviceType::PC;
        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        // 電子發票參數
        /*
        $sdk->Send['InvoiceMark'] = InvoiceState::Yes;
        $sdk->SendExtend['RelateNumber'] = $MerchantTradeNo;
        $sdk->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
        $sdk->SendExtend['CustomerPhone'] = '0911222333';
        $sdk->SendExtend['TaxType'] = TaxType::Dutiable;
        $sdk->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
        $sdk->SendExtend['InvoiceItems'] = array();
        // 將商品加入電子發票商品列表陣列
        foreach ($sdk->Send['Items'] as $info)
        {
            array_push($sdk->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => TaxType::Dutiable));
        }
        $sdk->SendExtend['InvoiceRemark'] = '測試發票備註';
        $sdk->SendExtend['DelayDay'] = '0';
        $sdk->SendExtend['InvType'] = InvType::General;
        */

        return $this->sdk->formToArray(
            $this->sdk->CheckOutString()
        );
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
        $this->sdk->ServiceURL = $this->getApiEndpoint('DoAction');
        $this->sdk->Action = array_replace(
            $this->sdk->Action,
            array_intersect_key($params, $this->sdk->Action)
        );

        return $this->sdk->DoAction();
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
        $this->sdk->ServiceURL = $this->getApiEndpoint('AioChargeback');
        $this->sdk->ChargeBack = array_replace(
            $this->sdk->ChargeBack,
            array_intersect_key($params, $this->sdk->ChargeBack)
        );

        return $this->sdk->AioChargeback();
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
        $this->sdk->ServiceURL = $this->getApiEndpoint('QueryTradeInfo');
        $this->sdk->Query['MerchantTradeNo'] = $params['MerchantTradeNo'];
        $details = $this->sdk->QueryTradeInfo();
        $details['RtnCode'] = $details['TradeStatus'] === '1' ? '1' : '2';

        return $details;
    }
}
