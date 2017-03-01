<?php

namespace PayumTW\Ecpay\Tests\Action;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Ecpay\Action\StatusLogisticsAction;

class StatusLogisticsActionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testMarkNew()
    {
        $this->validate([], 'markNew');
    }

    public function testMarkCaptured()
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

             'RtnCode' => '1',
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

    public function testMarkFailed()
    {
        $this->validate(['ErrorMessage' => '1'], 'markFailed');

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

    public function testMarkRefunded()
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

        $this->validate([
            'MerchantID' => '123456789',
            'AllPayLogisticsID' => 'Ecpay_1234',
            'ServerReplyURL' => '',
            'ServiceType' => '4',
            'SenderName' => 'foo',

            'RtnMerchantTradeNo' => '1',
            'RtnOrderNo' => 'OK',
        ], 'markRefunded');

        $this->validate([
            'MerchantID' => '123456789',
            'RtnMerchantTradeNo' => '1',

            'RtnCode' => '1',
            'RtnMsg' => 'OK',
        ], 'markRefunded');
    }

    public function testMarkCancel()
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

    public function testMarkUnknown()
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
        $action = new StatusLogisticsAction();
        $request = m::mock('Payum\Core\Request\GetStatusInterface');
        $request->shouldReceive('getModel')->andReturn($details = new ArrayObject($input));
        $request->shouldReceive($type)->once();

        $action->execute($request);
    }
}
