<?php

namespace PayumTW\Ecpay;

use Device;
use Exception;
use IsCollection;
use LogisticsType;
use LogisticsSubType;
use Http\Message\MessageFactory;
use Payum\Core\HttpClientInterface;
use PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics;

class LogisticsApi extends BaseApi
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
     * @var \PayumTW\Ecpay\Bridge\Ecpay\EcpayLogistics
     */
    protected $api;

    /**
     * @var array
     */
    protected $code = [];

    /**
     * @param array               $options
     * @param HttpClientInterface $client
     * @param MessageFactory      $messageFactory
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory, EcpayLogistics $api = null)
    {
        $this->options = $options;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
        $this->api = is_null($api) === true ? new EcpayLogistics() : $api;
        $this->api->HashKey = $this->options['HashKey'];
        $this->api->HashIV = $this->options['HashIV'];
        $this->api->Send['MerchantID'] = $this->options['MerchantID'];
    }

    /**
     * prepareMap.
     *
     * @method prepareMap
     *
     * @param array $params
     *
     * @return array
     */
    public function prepareMap(array $params)
    {
        $this->api->Send = array_merge($this->api->Send, [
            'ServerReplyURL'    => '',
            'MerchantTradeNo'   => '',
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'LogisticsSubType'  => LogisticsSubType::UNIMART,
            'IsCollection'      => IsCollection::NO,
            'Device'            => $this->isMobile() ? Device::MOBILE : Device::PC,
        ]);

        $this->api->Send = array_replace(
            $this->api->Send,
            array_intersect_key($params, $this->api->Send)
        );

        $params = $this->api->CvsMap();

        return [
            'apiEndpoint' => $this->api->ServiceURL,
            'params'      => $params,
        ];
    }

    /**
     * payment.
     *
     * @param array $params
     * @param mixed $request
     *
     * @return array
     */
    public function preparePayment(array $params)
    {
        $this->api->Send = array_merge($this->api->Send, [
            'MerchantTradeNo'      => '',
            'MerchantTradeDate'    => date('Y/m/d H:i:s'),
            'LogisticsType'        => '',
            'LogisticsSubType'     => LogisticsSubType::UNIMART,
            'GoodsAmount'          => 0,
            'CollectionAmount'     => 0,
            'IsCollection'         => IsCollection::NO,
            'GoodsName'            => '',
            'SenderName'           => '',
            'SenderPhone'          => '',
            'SenderCellPhone'      => '',
            'ReceiverName'         => '',
            'ReceiverPhone'        => '',
            'ReceiverCellPhone'    => '',
            'ReceiverEmail'        => '',
            'TradeDesc'            => '',
            'ServerReplyURL'       => '',
            'LogisticsC2CReplyURL' => '',
            'Remark'               => '',
            'PlatformID'           => '',
        ]);

        $this->api->SendExtend = [];

        $this->api->Send = array_replace(
            $this->api->Send,
            array_intersect_key($params, $this->api->Send)
        );

        if (empty($this->api->Send['LogisticsType']) === true) {
            $this->api->Send['LogisticsType'] = LogisticsType::CVS;
            switch ($this->api->Send['LogisticsSubType']) {
                case LogisticsSubType::TCAT:
                    $this->api->Send['LogisticsType'] = LogisticsType::HOME;
                    break;
            }
        }

        if ($this->api->Send['IsCollection'] === IsCollection::NO) {
            $this->api->Send['CollectionAmount'] = 0;
        } elseif (isset($this->api->Send['CollectionAmount']) === false) {
            $this->api->Send['CollectionAmount'] = (int) $this->api->Send['GoodsAmount'];
        }

        switch ($this->api->Send['LogisticsType']) {
            case LogisticsType::HOME:
                $this->api->SendExtend = array_merge($this->api->SendExtend, [
                    'SenderZipCode'         => '',
                    'SenderAddress'         => '',
                    'ReceiverZipCode'       => '',
                    'ReceiverAddress'       => '',
                    'Temperature'           => '',
                    'Distance'              => '',
                    'Specification'         => '',
                    'ScheduledDeliveryTime' => '',
                ]);
                break;
            case LogisticsType::CVS:
                $this->api->SendExtend = array_merge($this->api->SendExtend, [
                    'ReceiverStoreID' => '',
                    'ReturnStoreID'   => '',
                ]);
                break;
        }

        $this->api->SendExtend = array_replace(
            $this->api->SendExtend,
            array_intersect_key($params, $this->api->SendExtend)
        );

        return $this->api->BGCreateShippingOrder();
    }

    /**
     * Verify if the hash of the given parameter is correct.
     *
     * @param array $params
     *
     * @return bool
     */
    public function verifyHash(array $params)
    {
        $result = false;
        try {
            $this->api->CheckOutFeedback($params);
            $result = true;
        } catch (Exception $e) {
        }

        return $result;
    }

    /**
     * parseResult.
     *
     * @param mixed $params
     *
     * @return array
     */
    public function parseResult($params)
    {
        if (isset($params['CVSStoreID']) === true) {
            return $params;
        }

        if ($this->verifyHash($params) === false) {
            $params['RtnCode'] = '10400002';
        }

        return $params;
    }
}
