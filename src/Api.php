<?php

namespace PayumTW\Ecpay;

use Exception;
use Detection\MobileDetect;

abstract class Api
{
    /**
     * Verify if the hash of the given parameter is correct.
     *
     * @param array $params
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
     * isMobile.
     *
     * @return bool
     */
    protected function isMobile()
    {
        $detect = new MobileDetect();

        return ($detect->isMobile() === false && $detect->isTablet() === false) ? false : true;
    }
}
