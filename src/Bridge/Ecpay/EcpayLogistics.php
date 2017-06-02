<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

use ECPayLogistics as BaseECPayLogistics;

class EcpayLogistics extends BaseECPayLogistics
{
    use FormToArrayTrait;
}
