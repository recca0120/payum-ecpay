<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

use ReflectionObject;
use BadMethodCallException;

class EcpayLogistics extends \ECPayLogistics
{
    // 電子地圖
    public function CvsMap($ButtonDesc = '電子地圖', $Target = '_self')
    {
        // 參數初始化
        $ParamList = [
            'MerchantID'       => '',
            'MerchantTradeNo'  => '',
            'LogisticsSubType' => '',
            'IsCollection'     => '',
            'ServerReplyURL'   => '',
            'ExtraData'        => '',
            'Device'           => Device::PC,
        ];
        $this->PostParams = $this->parentGetPostParams($this->Send, $ParamList);
        $this->PostParams['LogisticsType'] = LogisticsType::CVS;

        // 參數檢查
        $this->parentValidateID('MerchantID', $this->PostParams['MerchantID'], 10);
        $this->ServiceURL = $this->parentGetURL('CVS_MAP');
        $this->parentValidateMerchantTradeNo();
        $this->parentValidateLogisticsSubType();
        $this->parentValidateIsCollection();
        $this->parentValidateURL('ServerReplyURL', $this->PostParams['ServerReplyURL']);
        $this->parentValidateString('ExtraData', $this->PostParams['ExtraData'], 20, true);
        $this->parentValidateDevice(true);

        return $this->PostParams;
    }

    protected function callParentPrivateMethod($method, array $parameters = [])
    {
        $reflectionObject = new ReflectionObject($this);
        $reflectionMethod = $reflectionObject->getMethod($method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($this, $parameters);
    }

    public function __call($method, $parameters)
    {
        if (strpos($method, 'parent') === 0) {
            return $this->callParentPrivateMethod(substr($method, 6), $parameters);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
