<?php

$classes = [
    'ECPay_ActionType',
    'ECPay_CarruerType',
    'ECPay_CheckMacValue',
    'ECPay_ClearanceMark',
    'ECPay_DeviceType',
    'ECPay_Donation',
    'ECPay_EncryptType',
    'ECPay_ExtraPaymentInfo',
    'ECPay_CheckOutFeedback',
    'ECPay_InvoiceState',
    'ECPay_InvType',
    'ECPay_PaymentMethod',
    'ECPay_PaymentMethodItem',
    'ECPay_PeriodType',
    'ECPay_PrintMark',
    'ECPay_TaxType',
    'ScheduledDeliveryTime',
    'IsCollection',
    'LogisticsSubType',
    'LogisticsType',
    'Device',
    'Distance',
    'ScheduledPickupTime',
    'Specification',
    'StoreType',
    'Temperature',
];

foreach ($classes as $class) {
    class_alias($class, 'PayumTW\Ecpay\Bridge\Ecpay\\'.str_replace('ECPay_', '', $class));
}
