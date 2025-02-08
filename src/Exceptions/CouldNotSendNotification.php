<?php

namespace NotificationChannels\SmsGet\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * 當服務端返回錯誤時拋出此異常.
     *
     * @param mixed $response 服務端回應內容
     */
    public static function serviceRespondedWithAnError(string $response): static
    {
        return new static('發送通知時，服務端返回錯誤: ' . $response);
    }

    /**
     * 當缺少收件人資訊時拋出此異常.
     */
    public static function missingTo(): static
    {
        return new static("缺少收件人電話號碼。請確保提供有效的 'to' 參數。");
    }

    /**
     * 當電話號碼格式無效時拋出此異常.
     */
    public static function invalidPhoneNumber(): static
    {
        return new static('提供的電話號碼格式無效。請確認是否為正確的國際格式，例如 +886912345678。');
    }

    /**
     * 當訊息物件格式不符合要求時拋出此異常.
     */
    public static function invalidMessageObject(): static
    {
        return new static("訊息物件無效。請確保傳遞的訊息符合 API 要求，例如包含 'content' 或 'text' 屬性。");
    }
}
