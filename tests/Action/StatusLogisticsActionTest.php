<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\StatusLogisticsAction;

class StatusLogisticsActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_mark_new()
    {
        $this->validate([], 'markNew');
    }

    public function test_mark_failed_when_error_message_exists()
    {
        $this->validate(['ErrorMessage' => '1'], 'markFailed');
    }

    public function test_make_captured_when_cvs_is_success()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'MerchantTradeNo' => 'Ecpay_1234',
            'LogisticsType' => 'CVS',
            'LogisticsSubType' => 'CVS',
            'IsCollection' => 'N',
            'ServerReplyURL' => 'foo',
            'CVSStoreID' => 'foo',
            'CVSStoreName' => 'foo',
            'CVSAddress' => 'foo',
            'CVSTelephone' => 'foo',
            'ExtraData' => 'foo',
        ], 'markCaptured');
    }

    public function test_mark_captured_when_order_create_success()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'MerchantTradeNo' => 'Ecpay_1234',
            'MerchantTradeDate' => '2012/03/21 15:40:18',
            'LogisticsType' => 'CVS',
            'LogisticsSubType' => 'FAMI',
            'GoodsAmount' => '5000',
            'SenderName' => 'foo',
            'ReceiverName' => '',
            'ServerReplyURL' => '',

            'ResCode' => '1',
            'RtnCode' => '100',
            'RtnMsg' => 'paid',
            'AllPayLogisticsID' => '10035',
            'UpdateStatusDate' => 'yyyy/MM/dd HH:mm:ss',
            'ReceiverName' => '收件人姓名',
            'ReceiverPhone' => '收件人電話',
            'ReceiverCellPhone' => '0987654321',
            'ReceiverEmail' => '收件人 email',
            'ReceiverAddress' => '收件人地址',
            'CVSPaymentNo' => '寄貨編號',
            'CVSValidationNo' => '驗證碼',
            'BookingNote' => '托運單號',
            'CheckMacValue' => '檢查碼',
         ], 'markCaptured');
    }

    public function test_mark_failed_when_order_create_fail()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'MerchantTradeNo' => 'Ecpay_1234',
            'MerchantTradeDate' => '2012/03/21 15:40:18',
            'LogisticsType' => 'CVS',
            'LogisticsSubType' => 'FAMI',
            'GoodsAmount' => '5000',
            'SenderName' => 'foo',
            'ReceiverName' => '',
            'ServerReplyURL' => '',

            'ResCode' => '0',
            'RtnCode' => '0',
            'RtnMsg' => 'paid',
            'AllPayLogisticsID' => '10035',
            'UpdateStatusDate' => 'yyyy/MM/dd HH:mm:ss',
            'ReceiverName' => '收件人姓名',
            'ReceiverPhone' => '收件人電話',
            'ReceiverCellPhone' => '0987654321',
            'ReceiverEmail' => '收件人 email',
            'ReceiverAddress' => '收件人地址',
            'CVSPaymentNo' => '寄貨編號',
            'CVSValidationNo' => '驗證碼',
            'BookingNote' => '托運單號',
            'CheckMacValue' => '檢查碼',
        ], 'markFailed');
    }

    public function test_mark_refunded_when_return_home()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'AllPayLogisticsID' => 'Ecpay_1234',
            'ServerReplyURL' => '',
            'GoodsAmount' => '5000',
            'Temperature' => 'foo',
            'Distance' => '',
            'Specification' => '',

            'RtnCode' => '1',
            'RtnMsg' => 'OK',
        ], 'markRefunded');
    }

    public function test_mark_refunded_when_return_cvs()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'AllPayLogisticsID' => 'Ecpay_1234',
            'ServerReplyURL' => '',
            'ServiceType' => '4',
            'SenderName' => 'foo',

            'RtnMerchantTradeNo' => '1',
            'RtnOrderNo' => 'OK',
        ], 'markRefunded');
    }

    public function test_mark_refunded_when_check_account()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'RtnMerchantTradeNo' => '1',

            'RtnCode' => '1',
            'RtnMsg' => 'OK',
        ], 'markRefunded');
    }

    public function test_mark_cancel_when_cancel_c2c_order()
    {
        $this->validate([
            'MerchantID' => '123456789',
            'AllPayLogisticsID' => '1',
            'CVSPaymentNo' => '',
            'CVSValidationNo' => '',

            'RtnCode' => '1',
            'RtnMsg' => 'OK',
        ], 'markCanceled');
    }

    public function test_mark_unknown_when_status_changed()
    {
        $this->validate([
            'MerchantID' => '廠商編號',
            'MerchantTradeNo' => '廠商交易編號',
            'RtnCode' => '100',
            'RtnMsg' => '物流狀態說明',
            'AllPayLogisticsID' => 'Ecpay 的物流 交易編號',
            'LogisticsType' => '物流類型',
            'LogisticsSubType' => '物流子類型',
            'GoodsAmount' => '商品金額',
            'UpdateStatusDate' => '物流狀態更新',
            'ReceiverName' => '收件人姓名',
            'ReceiverPhone' => '收件人電話',
            'ReceiverCellPhone' => '收件人手機',
            'ReceiverEmail' => '收件人 email',
            'ReceiverAddress' => '收件人地址',
            'CVSPaymentNo => ' => '寄貨編號',
            'CVSValidationNo' => '驗證碼',
            'BookingNote' => '托運單號',
            'CheckMacValue' => '檢查碼',
        ], 'markUnknown');
    }

    protected function validate($input, $type)
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\GetStatusInterface');
        $details = new ArrayObject($input);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request->shouldReceive('getModel')->andReturn($details);

        $action = new StatusLogisticsAction();
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getModel')->twice();
        $request->shouldHaveReceived($type)->once();
    }
}
