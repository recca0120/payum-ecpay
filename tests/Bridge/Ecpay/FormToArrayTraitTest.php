<?php

use Mockery as m;
use PayumTW\Ecpay\Bridge\Ecpay\FormToArrayTrait;

class FormToArrayTraitTest extends PHPUnit_Framework_TestCase
{
    use FormToArrayTrait;

    public function tearDown()
    {
        m::close();
    }

    public function test_cvs_map()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $form = '<div style="text-align:center;">
          <form id="ECPayForm" method="POST" action="https://logistics.ecpay.com.tw/Express/map" target="_self">
            <input type="hidden" name="MerchantID" value="2000132" />
            <input type="hidden" name="MerchantTradeNo" value="MerchantTradeNo" />
            <input type="hidden" name="LogisticsSubType" value="UNIMARTC2C" />
            <input type="hidden" name="IsCollection" value="N" />
            <input type="hidden" name="ServerReplyURL" value="http://dev/" />
            <input type="hidden" name="ExtraData" value="ExtraData" />
            <input type="hidden" name="Device" value="0" />
            <input type="hidden" name="LogisticsType" value="CVS" />
            <input type="submit" id="__paymentButton" value="?餃??啣?" />
          </form>
        </div>';

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertTrue(is_array($this->formToArray($form)));
    }
}
