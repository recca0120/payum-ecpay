<?php

$classes = [
    'ECPay_PaymentMethod',
    'ECPay_PaymentMethodItem',
    'ECPay_ExtraPaymentInfo',
    'ECPay_DeviceType',
    'ECPay_ActionType',
    'ECPay_PeriodType',
    'ECPay_InvoiceState',
    'ECPay_CarrierType',
    'ECPay_PrintMark',
    'ECPay_Donation',
    'ECPay_ClearanceMark',
    'ECPay_TaxType',
    'ECPay_InvType',
    'ECPay_EncryptType',
    'ECPay_Aio',
    'ECPay_Send',
    'ECPay_CheckOutFeedback',
    'ECPay_QueryTradeInfo',
    'ECPay_QueryPeriodCreditCardTradeInfo',
    'ECPay_DoAction',
    'ECPay_AioCapture',
    'ECPay_Verification',
    'ECPay_CVS',
    'ECPay_BARCODE',
    'ECPay_ATM',
    'ECPay_WebATM',
    'ECPay_Credit',
    'ECPay_ALL',
    'ECPay_CheckMacValue',
    'LogisticsType',
    'LogisticsSubType',
    'IsCollection',
    'Device',
    'ECPayTestMerchantID',
    'ECPayURL',
    'ECPayTestURL',
    'Temperature',
    'Distance',
    'Specification',
    'ScheduledPickupTime',
    'ScheduledDeliveryTime',
    'StoreType',
    'ECPayLogistics',
];

foreach ($classes as $class) {
    class_alias($class, 'PayumTW\Ecpay\Bridge\Ecpay\\'.str_replace('ECPay_', '', $class));
}
