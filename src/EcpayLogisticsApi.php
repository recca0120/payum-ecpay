<?php

namespace PayumTW\Ecpay;

use Http\Message\MessageFactory;
use Payum\Core\HttpClientInterface;
use PayumTW\Ecpay\Bridge\Ecpay\Device;
use PayumTW\Ecpay\Bridge\Ecpay\Distance;
use PayumTW\Ecpay\Bridge\Ecpay\Temperature;
use PayumTW\Ecpay\Bridge\Ecpay\IsCollection;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsType;
use PayumTW\Ecpay\Bridge\Ecpay\Specification;
use PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics;
use PayumTW\Ecpay\Bridge\Ecpay\LogisticsSubType;
use PayumTW\Ecpay\Bridge\Ecpay\ScheduledPickupTime;

class EcpayLogisticsApi extends Api
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
     * @var \PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics
     */
    protected $sdk;

    /**
     * @var array
     */
    protected $code = [];

    /**
     * @param array $options
     * @param HttpClientInterface $client
     * @param MessageFactory $messageFactory
     * @param \PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics $sdk
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory, EcpayLogistics $sdk = null)
    {
        $this->options = $options;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
        $this->sdk = $sdk ?: new EcpayLogistics();
        $this->sdk->HashKey = $this->options['HashKey'];
        $this->sdk->HashIV = $this->options['HashIV'];
        $this->sdk->Send['MerchantID'] = $this->options['MerchantID'];
    }

    /**
     * getApiEndpoint.
     *
     * @return string
     */
    public function getApiEndpoint($name = 'AioCheckOut')
    {
        return $this->sdk->ServiceURL;
    }

    /**
     * createTransaction.
     *
     * @param array $params
     * @param mixed $request
     *
     * @return array
     */
    public function createCvsMapTransaction(array $params)
    {
        $this->sdk->Send = array_merge($this->sdk->Send, [
            'MerchantTradeNo' => '',
            'LogisticsSubType' => LogisticsSubType::UNIMART,
            'IsCollection' => IsCollection::NO,
            'ServerReplyURL' => '',
            'ExtraData' => '',
            'Device' => $this->isMobile() ? Device::MOBILE : Device::PC,
        ]);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return $this->sdk->formToArray(
            $this->sdk->CvsMap()
        );
    }

    /**
     * 產生托運單(宅配)/一段標(超商取貨).
     *
     * @param array $params
     * @return [type]         [description]
     */
    public function createPrintTradeTransaction(array $params)
    {
        // 參數初始化
        $this->sdk->Send = array_merge($this->sdk->Send, [
            'AllPayLogisticsID' => '',
            'PlatformID'        => '',
        ]);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return $this->sdk->formToArray(
            $this->sdk->PrintTradeDoc()
        );
    }

    /**
     * 列印繳款單(統一超商C2C).
     *
     * @param array $params
     * @return array
     */
    public function createPrintUnimartC2CBillTransaction(array $params)
    {
        // 參數初始化
        $this->sdk->Send = array_merge($this->sdk->Send, [
            'AllPayLogisticsID' => '',
            'CVSPaymentNo'      => '',
            'CVSValidationNo'   => '',
            'PlatformID'        => '',
        ]);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return $this->sdk->formToArray(
            $this->sdk->PrintUnimartC2CBill()
        );
    }

    /**
     * 全家列印小白單(全家超商C2C).
     *
     * @param array $params
     * @return array
     */
    public function createPrintFamilyC2CBillTransaction(array $params)
    {
        // 參數初始化
        $this->sdk->Send = array_merge($this->sdk->Send, [
            'AllPayLogisticsID' => '',
            'CVSPaymentNo'      => '',
            'PlatformID'        => '',
        ]);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return $this->sdk->formToArray(
            $this->sdk->PrintUnimartC2CBill()
        );
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
        $this->sdk->Send = array_merge($this->sdk->Send, [
            'MerchantTradeNo' => '',
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'LogisticsType' => '',
            'LogisticsSubType' => LogisticsSubType::UNIMART,
            'GoodsAmount' => 0,
            'CollectionAmount' => 0,
            'IsCollection' => IsCollection::NO,
            'GoodsName' => '',
            'SenderName' => '',
            'SenderPhone' => '',
            'SenderCellPhone' => '',
            'ReceiverName' => '',
            'ReceiverPhone' => '',
            'ReceiverCellPhone' => '',
            'ReceiverEmail' => '',
            'TradeDesc' => '',
            'ServerReplyURL' => '',
            'LogisticsC2CReplyURL' => '',
            'Remark' => '',
            'PlatformID' => '',
        ]);

        $this->sdk->SendExtend = [];

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        $this->sdk->Send['GoodsAmount'] = (int) $this->sdk->Send['GoodsAmount'];
        $this->sdk->Send['CollectionAmount'] = (int) $this->sdk->Send['CollectionAmount'];

        if (empty($this->sdk->Send['LogisticsType']) === true) {
            $this->sdk->Send['LogisticsType'] = LogisticsType::CVS;
            switch ($this->sdk->Send['LogisticsSubType']) {
                case LogisticsSubType::TCAT:
                    $this->sdk->Send['LogisticsType'] = LogisticsType::HOME;
                    break;
            }
        }

        if ($this->sdk->Send['IsCollection'] === IsCollection::NO) {
            $this->sdk->Send['CollectionAmount'] = 0;
        } elseif (isset($this->sdk->Send['CollectionAmount']) === false) {
            $this->sdk->Send['CollectionAmount'] = (int) $this->sdk->Send['GoodsAmount'];
        }

        switch ($this->sdk->Send['LogisticsType']) {
            case LogisticsType::HOME:
                $this->sdk->SendExtend = array_merge($this->sdk->SendExtend, [
                    'SenderZipCode' => '',
                    'SenderAddress' => '',
                    'ReceiverZipCode' => '',
                    'ReceiverAddress' => '',
                    'Temperature' => '',
                    'Distance' => '',
                    'Specification' => '',
                    'ScheduledDeliveryTime' => '',
                ]);
                break;
            case LogisticsType::CVS:
                $this->sdk->SendExtend = array_merge($this->sdk->SendExtend, [
                    'ReceiverStoreID' => '',
                    'ReturnStoreID' => '',
                ]);
                break;
        }

        $this->sdk->SendExtend = array_replace(
            $this->sdk->SendExtend,
            array_intersect_key($params, $this->sdk->SendExtend)
        );

        return $this->sdk->BGCreateShippingOrder();
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
        if ($params['LogisticsSubType'] === LogisticsSubType::TCAT) {
            // 宅配逆物流訂單產生
            $method = 'CreateHomeReturnOrder';
            $supportedParams = [
                'AllPayLogisticsID'     => '',
                'LogisticsSubType'      => '',
                'ServerReplyURL'        => '',
                'SenderName'            => '',
                'SenderPhone'           => '',
                'SenderCellPhone'       => '',
                'SenderZipCode'         => '',
                'SenderAddress'         => '',
                'ReceiverName'          => '',
                'ReceiverPhone'         => '',
                'ReceiverCellPhone'     => '',
                'ReceiverZipCode'       => '',
                'ReceiverAddress'       => '',
                'GoodsAmount'           => '',
                'GoodsName'             => '',
                'Temperature'           => Temperature::ROOM,
                'Distance'              => Distance::SAME,
                'Specification'         => Specification::CM_60,
                'ScheduledPickupTime'   => ScheduledPickupTime::UNLIMITED,
                'ScheduledDeliveryTime' => '',
                'Remark'                => '',
                'PlatformID'            => '',
            ];
        } elseif (isset($params['LogisticsSubType']) === true) {
            // LogisticsSubType::FAMILY = 'FAMI'; // 全家
            // LogisticsSubType::UNIMART = 'UNIMART'; // 統一超商
            // LogisticsSubType::FAMILY_C2C = 'FAMIC2C'; // 全家店到店
            // LogisticsSubType::UNIMART_C2C = 'UNIMARTC2C'; // 統一超商寄貨便

            // 超商取貨逆物流訂單(全家超商B2C).
            $method = 'CreateFamilyB2CReturnOrder';
            $supportedParams = [
                'AllPayLogisticsID' => '',
                'ServerReplyURL'    => '',
                'GoodsName'         => '',
                'GoodsAmount'       => 0,
                'SenderName'        => '',
                'SenderPhone'       => '',
                'Remark'            => '',
                'Quantity'          => '',
                'Cost'              => '',
                'PlatformID'        => '',
            ];
        } else {
            // 全家逆物流核帳(全家超商B2C).
            $method = 'CheckFamilyB2CLogistics';
            $supportedParams = [
                'RtnMerchantTradeNo' => '',
                'PlatformID'         => '',
            ];
        }

        $this->sdk->Send = array_merge($this->sdk->Send, $supportedParams);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return call_user_func_array([$this->sdk, $method]);
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
        if (isset($params['LogisticsSubType']) === true) {
            // LogisticsSubType::FAMILY = 'FAMI'; // 全家
            // LogisticsSubType::UNIMART = 'UNIMART'; // 統一超商
            // LogisticsSubType::FAMILY_C2C = 'FAMIC2C'; // 全家店到店
            // LogisticsSubType::UNIMART_C2C = 'UNIMARTC2C'; // 統一超商寄貨便

            // 取消訂單(統一超商C2C).
            $method = 'CancelUnimartLogisticsOrder';
            $supportedParams = [
                'AllPayLogisticsID' => '',
                'CVSPaymentNo'      => '',
                'CVSValidationNo'   => '',
                'PlatformID'        => '',
            ];
        }

        $this->sdk->Send = array_merge($this->sdk->Send, $supportedParams);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return call_user_func_array([$this->sdk, $method]);
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
        $supportedParams = [
            'AllPayLogisticsID' => '',
            'PlatformID' => '',
        ];

        $this->sdk->Send = array_merge($this->sdk->Send, $supportedParams);

        $this->sdk->Send = array_replace(
            $this->sdk->Send,
            array_intersect_key($params, $this->sdk->Send)
        );

        return $this->sdk->QueryLogisticsInfo();
    }
}
