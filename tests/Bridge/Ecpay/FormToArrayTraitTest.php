<?php

namespace PayumTW\Ecpay\Tests\Bridge\Ecpay;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use PayumTW\Ecpay\Bridge\Ecpay\FormToArrayTrait;

class FormToArrayTraitTest extends TestCase
{
    use FormToArrayTrait;

    protected function tearDown()
    {
        m::close();
    }

    public function testAllInOne()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $form = '<html>
                <head>
                    <meta charset="utf-8">
                </head>
                <body>
                    <form id="__allpayForm" method="post" target="_self" action="https://payment.allpay.com.tw/Cashier/AioCheckOut/V2">
                        <input type="hidden" name="MerchantID" value=\'foo\' />
                        <input type="hidden" name="ReturnURL" value=\'http://dev/\' />
                        <input type="hidden" name="ClientBackURL" value=\'\' />
                        <input type="hidden" name="OrderResultURL" value=\'http://dev/\' />
                        <input type="hidden" name="MerchantTradeNo" value=\'foo\' />
                        <input type="hidden" name="MerchantTradeDate" value=\'2016/12/16 12:57:17\' />
                        <input type="hidden" name="PaymentType" value=\'aio\' />
                        <input type="hidden" name="TotalAmount" value=\'340\' />
                        <input type="hidden" name="TradeDesc" value=\'foo\' />
                        <input type="hidden" name="ChoosePayment" value=\'ALL\' />
                        <input type="hidden" name="Remark" value=\'\' />
                        <input type="hidden" name="ChooseSubPayment" value=\'\' />
                        <input type="hidden" name="NeedExtraPaidInfo" value=\'N\' />
                        <input type="hidden" name="DeviceSource" value=\'P\' />
                        <input type="hidden" name="IgnorePayment" value=\'Alipay#Tenpay#ATM#CVS\' />
                        <input type="hidden" name="InvoiceMark" value=\'\' />
                        <input type="hidden" name="EncryptType" value=\'0\' />
                        <input type="hidden" name="UseRedeem" value=\'N\' />
                        <input type="hidden" name="ItemURL" value=\'dedwed\' />
                        <input type="hidden" name="ItemName" value=\'foo\' />
                        <input type="hidden" name="AlipayItemName" value=\'foo\' />
                        <input type="hidden" name="AlipayItemCounts" value=\'1#1\' />
                        <input type="hidden" name="AlipayItemPrice" value=\'220#120\' />
                        <input type="hidden" name="CheckMacValue" value="foo" />
                        <input type="submit" id="__paymentButton" value="" />
                    </form>
                </body>
            </html>';
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

        $this->assertSame([
            'MerchantID' => 'foo',
            'ReturnURL' => 'http://dev/',
            'ClientBackURL' => '',
            'OrderResultURL' => 'http://dev/',
            'MerchantTradeNo' => 'foo',
            'MerchantTradeDate' => '2016/12/16 12:57:17',
            'PaymentType' => 'aio',
            'TotalAmount' => '340',
            'TradeDesc' => 'foo',
            'ChoosePayment' => 'ALL',
            'Remark' => '',
            'ChooseSubPayment' => '',
            'NeedExtraPaidInfo' => 'N',
            'DeviceSource' => 'P',
            'IgnorePayment' => 'Alipay#Tenpay#ATM#CVS',
            'InvoiceMark' => '',
            'EncryptType' => '0',
            'UseRedeem' => 'N',
            'ItemURL' => 'dedwed',
            'ItemName' => 'foo',
            'AlipayItemName' => 'foo',
            'AlipayItemCounts' => '1#1',
            'AlipayItemPrice' => '220#120',
            'CheckMacValue' => 'foo',
        ], $this->formToArray($form));
    }

    public function testlogistics()
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

        $this->assertSame([
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'MerchantTradeNo',
            'LogisticsSubType' => 'UNIMARTC2C',
            'IsCollection' => 'N',
            'ServerReplyURL' => 'http://dev/',
            'ExtraData' => 'ExtraData',
            'Device' => '0',
            'LogisticsType' => 'CVS',
        ], $this->formToArray($form));
    }
}
