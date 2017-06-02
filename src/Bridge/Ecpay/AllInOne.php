<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

use ECPay_AllInOne;
use ECPay_CheckOutFeedback;

class AllInOne extends ECPay_AllInOne
{
    use FormToArrayTrait;

    /**
     * 取得付款結果通知的方法.
     */
    public function CheckOutFeedback()
    {
        $params = func_get_arg(0);

        return ECPay_CheckOutFeedback::CheckOut($params, $this->HashKey, $this->HashIV, 0);
    }
}
