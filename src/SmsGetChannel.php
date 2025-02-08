<?php

namespace NotificationChannels\SmsGet;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use NotificationChannels\SmsGet\Exceptions\CouldNotSendNotification;

class SmsGetChannel
{
    public function __construct(
        protected SmsGet $client,
    ) {
    }

    /**
     * Send the given notification.
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('smsget');

        if (! $to) {
            throw CouldNotSendNotification::missingTo();
        }

        $phone = $this->parsePhoneNumber($to);

        if ($phone->getCountryCode() === 886) {
            $to = $this->formatTaiwanPhoneNumber($phone);
        } else {
            $to = $this->formatGlobalPhoneNumber($phone);
        }

        if (method_exists($notification, 'toSmsGet')) {
            throw CouldNotSendNotification::invalidMessageObject();
        }

        $message = $notification->toSmsGet($notifiable);

        if (is_string($message)) {
            $message = new SmsGetMessage($message);
        }

        try {
            $response = $this->client->send($to, $message->content);
            $response = json_decode($response, true);

            if ($response['stats'] != 'True') {
                throw CouldNotSendNotification::serviceRespondedWithAnError($response['error_msg']);
            }
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception->getMessage());
        }
    }

    protected function parsePhoneNumber(string $to): PhoneNumber
    {
        try {
            return PhoneNumberUtil::getInstance()->parse($to);
        } catch (NumberParseException $e) {
            throw CouldNotSendNotification::invalidPhoneNumber();
        }
    }

    protected function formatTaiwanPhoneNumber(PhoneNumber $phone): string
    {
        $number = $phone->getNationalNumber();

        return "0{$number}";
    }

    protected function formatGlobalPhoneNumber(PhoneNumber $phone): string
    {
        $number = PhoneNumberUtil::getInstance()->format($phone, PhoneNumberFormat::E164);

        if (in_array($phone->getCountryCode(), [86, 852])) {
            $number = str_replace($phone->getCountryCode(), '', $number);
        }

        return preg_replace('/\D/', '', $number);
    }
}
