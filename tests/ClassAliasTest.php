<?php

use Mockery as m;

class ClassAliasTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_ActionType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ActionType'));
    }

    public function test_CarruerType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\CarruerType'));
    }

    public function test_ClearanceMark_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ClearanceMark'));
    }

    public function test_DeviceType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\DeviceType'));
    }

    public function test_Donation_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Donation'));
    }

    public function test_EncryptType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\EncryptType'));
    }

    public function test_ExtraPaymentInfo_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ExtraPaymentInfo'));
    }

    public function test_InvoiceState_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\InvoiceState'));
    }

    public function test_InvType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\InvType'));
    }

    public function test_PaymentMethod_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PaymentMethod'));
    }

    public function test_PaymentMethodItem_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PaymentMethodItem'));
    }

    public function test_PeriodType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PeriodType'));
    }

    public function test_PrintMark_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PrintMark'));
    }

    public function test_TaxType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\TaxType'));
    }

    public function test_ScheduledDeliveryTime_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ScheduledDeliveryTime'));
    }

    public function test_IsCollection_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\IsCollection'));
    }

    public function test_LogisticsSubType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\LogisticsSubType'));
    }

    public function test_LogisticsType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\LogisticsType'));
    }

    public function test_Device_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Device'));
    }

    public function test_Distance_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Distance'));
    }

    public function test_ScheduledPickupTime_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ScheduledPickupTime'));
    }

    public function test_Specification_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Specification'));
    }

    public function test_StoreType_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\StoreType'));
    }

    public function test_Temperature_exists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Temperature'));
    }
}
