<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

use ReflectionObject;
use BadMethodCallException;

class EcpayLogistics extends \ECPayLogistics
{
    /**
     * 電子地圖.
     *
     * @param string $ButtonDesc
     * @param string $Target
     */
    public function CvsMap($ButtonDesc = '電子地圖', $Target = '_self')
    {
        return $this->formToArray(parent::CvsMap($ButtonDesc, $Target));
    }

    /**
     * 產生托運單(宅配)/一段標(超商取貨).
     *
     * @param string $ButtonDesc
     * @param string $Target
     */
    public function PrintTradeDoc($ButtonDesc = '產生托運單/一段標', $Target = '_blank')
    {
        return $this->formToArray(parent::PrintTradeDoc($ButtonDesc, $Target));
    }

    /**
     * 列印繳款單(統一超商C2C).
     *
     * @param string $ButtonDesc
     * @param string $Target
     */
    public function PrintUnimartC2CBill($ButtonDesc = '列印繳款單(統一超商C2C)', $Target = '_blank')
    {
        return $this->formToArray(parent::PrintUnimartC2CBill($ButtonDesc, $Target));
    }

    /**
     * 全家列印小白單(全家超商C2C).
     *
     * @param string $ButtonDesc
     * @param string $Target
     */
    public function PrintFamilyC2CBill($ButtonDesc = '全家列印小白單(全家超商C2C)', $Target = '_blank')
    {
        return $this->formToArray(parent::PrintFamilyC2CBill($ButtonDesc, $Target));
    }

    /**
     * formTOArray.
     *
     * @param string $form
     */
    public function formToArray($form) {
        $result = [];
        if (preg_match_all('/<input[^>]*>/sm', $form, $inputs) !== false) {
            foreach ($inputs[0] as $input) {
                if (preg_match('/name="([^"]+)"\s+value="([^"]+)"/', $input, $match) !== false) {
                    if (count($match) === 3) {
                        $result[$match[1]] = $match[2];
                    }
                }
            }
        }

        return $result;
    }
}
