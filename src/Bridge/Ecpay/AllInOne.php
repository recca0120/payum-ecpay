<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

class AllInOne extends \ECPay_AllInOne
{
    //產生訂單
    public function CheckOut($target = '_self')
    {
        $arParameters = array_merge(['MerchantID' => $this->MerchantID], $this->Send);

        return Send::CheckOut($target, $arParameters, $this->SendExtend, $this->HashKey, $this->HashIV, $this->ServiceURL);
    }

    //取得付款結果通知的方法
    public function CheckOutFeedback()
    {
        $params = func_get_arg(0);

        return $arFeedback = CheckOutFeedback::CheckOut($params, $this->HashKey, $this->HashIV, 0);
    }
}
