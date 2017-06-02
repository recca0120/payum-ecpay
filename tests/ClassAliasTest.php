<?php

namespace PayumTW\Ecpay\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

class ClassAliasTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testActionTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ActionType'));
    }

    public function testClearanceMarkExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ClearanceMark'));
    }

    public function testDeviceTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\DeviceType'));
    }

    public function testDonationExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Donation'));
    }

    public function testEncryptTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\EncryptType'));
    }

    public function testExtraPaymentInfoExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ExtraPaymentInfo'));
    }

    public function testInvoiceStateExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\InvoiceState'));
    }

    public function testInvTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\InvType'));
    }

    public function testPaymentMethodExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PaymentMethod'));
    }

    public function testPaymentMethodItemExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PaymentMethodItem'));
    }

    public function testPeriodTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PeriodType'));
    }

    public function testPrintMarkExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\PrintMark'));
    }

    public function testTaxTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\TaxType'));
    }

    public function testScheduledDeliveryTimeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ScheduledDeliveryTime'));
    }

    public function testIsCollectionExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\IsCollection'));
    }

    public function testLogisticsSubTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\LogisticsSubType'));
    }

    public function testLogisticsTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\LogisticsType'));
    }

    public function testDeviceExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Device'));
    }

    public function testDistanceExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Distance'));
    }

    public function testScheduledPickupTimeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\ScheduledPickupTime'));
    }

    public function testSpecificationExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Specification'));
    }

    public function testStoreTypeExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\StoreType'));
    }

    public function testTemperatureExists()
    {
        $this->assertTrue(class_exists('PayumTW\Ecpay\Bridge\Ecpay\Temperature'));
    }
}
