<?php

namespace NotificationChannels\SmsGet\Test;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function testFormatTaiwanPhoneNumber()
    {
        $number = PhoneNumberHelper::formatTaiwanPhoneNumber(PhoneNumberUtil::getInstance()->parse('+886912345678'));

        $this->assertEquals('0912345678', $number);
    }

    public function testFormatGlobalPhoneNumber()
    {
        $number = PhoneNumberHelper::formatGlobalPhoneNumber(PhoneNumberUtil::getInstance()->parse('+8618819139907'));

        $this->assertEquals('18819139907', $number);

        $number = PhoneNumberHelper::formatGlobalPhoneNumber(PhoneNumberUtil::getInstance()->parse('+85291234567'));

        $this->assertEquals('91234567', $number);

        $number = PhoneNumberHelper::formatGlobalPhoneNumber(PhoneNumberUtil::getInstance()->parse('+14155552671'));

        $this->assertEquals('14155552671', $number);
    }
}

class PhoneNumberHelper
{
    public static function formatTaiwanPhoneNumber(PhoneNumber $phone): string
    {
        $number = $phone->getNationalNumber();

        return "0{$number}";
    }

    public static function formatGlobalPhoneNumber(PhoneNumber $phone): string
    {
        $number = PhoneNumberUtil::getInstance()->format($phone, PhoneNumberFormat::E164);

        if (in_array($phone->getCountryCode(), [86, 852])) {
            $number = str_replace($phone->getCountryCode(), '', $number);
        }

        return preg_replace('/\D/', '', $number);
    }
}
