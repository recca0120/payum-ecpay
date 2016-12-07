<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

class Send extends \ECPay_Send
{
    public static function CheckOut($target = '_self', $arParameters = [], $arExtend = [], $HashKey = '', $HashIV = '', $ServiceURL = '')
    {
        $arParameters = self::process($arParameters, $arExtend);

        //產生檢查碼
        $arParameters['CheckMacValue'] = CheckMacValue::generate($arParameters, $HashKey, $HashIV, $arParameters['EncryptType']);

        return $arParameters;
    }
}
