<?php

namespace PayumTW\Ecpay\Bridge\Ecpay;

trait FormToArrayTrait
{
    /**
     * formTOArray.
     *
     * @param string $form
     * @return array
     */
    public function formToArray($form)
    {
        $result = [];
        if (preg_match_all('/<input[^>]*>/sm', $form, $inputs) !== false) {
            foreach ($inputs[0] as $input) {
                if (preg_match('/name=["\']([^"\']*)["\']\s+value=["\']([^"\']*)["\']/', $input, $match) !== false) {
                    if (count($match) === 3) {
                        $result[$match[1]] = $match[2];
                    }
                }
            }
        }

        return $result;
    }
}
