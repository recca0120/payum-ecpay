<?php

namespace PayumTW\Ecpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusLogisticsAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        // 300 訂單處理中(已收到訂單資料) 訂單處理中
        // 310 上傳電子訂單檔處理中 訂單處理中
        // 311 上傳退貨電子訂單處理中 退貨訂單處理中
        // 325 退貨訂單處理中(已收到訂單資料 退貨訂單處理中
        // 2000 出貨訂單修改 出貨訂單修改
        // 2001 檔案傳送成功 不建議呈現

        if (isset($details['RtnCode']) === true && in_array($details['RtnCode'], ['300', '310', '311', '325', '2000', '2001'], true) === true) {
            $request->markCaptured();

            return;
        }

        // [
        //     "MerchantID" => "2000132"
        //     "MerchantTradeNo" => "57CBD972925C4"
        //     "LogisticsSubType" => "FAMI"
        //     "CVSStoreID" => "015074"
        //     "CVSStoreName" => "全家基隆中船店"
        //     "CVSAddress" => "基隆市中正區中船路88號"
        //     "CVSTelephone" => "02-24245729"
        //     "ExtraData" => ""
        // ];
        if (isset($details['CVSStoreID']) === true) {
            $request->markCaptured();

            return;
        }

        if (isset($details['CVSStoreID']) === false && isset($details['RtnCode']) === false) {
            $request->markNew();

            return;
        }

        $request->markFailed();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
