<?php

namespace  Services\Messages;

use  Services\Messages\Types\ClientPriceChangedAfterReload;
use  Services\Messages\Types\CmrCopy;
use  Services\Messages\Types\CopiedOrderToCheck;
use  Services\Messages\Types\CurrenciesRatesLoadError;
use  Services\Messages\Types\CurrenciesRatesLoadErrorAdmin;
use  Services\Messages\Types\MarginAttribution;
use  Services\Messages\Types\NewAccountToApprove;
use  Services\Messages\Types\NotCompletedOrder;
use  Services\Messages\Types\NotCompletedUserOrder;
use  Services\Messages\Types\OrderCancellation;
use  Services\Messages\Types\OrderCancellationAccept;
use  Services\Messages\Types\OrderCorrectionToZero;
use  Services\Messages\Types\OrderToFill;
use  Services\Messages\Types\PlateNumberToComplete;
use  Services\Messages\Types\PlateNumberToCompleteUser;
use OutOfRangeException;

abstract class MessagesType
{
    const CLIENT_PRICE_CHANGED = 1;
    const NOT_COMPLETED_ORDER = 2;
    const NOT_COMPLETED_USER_ORDER = 3;
    const CURRENCIES_RATE_ERROR = 4;
    const CURRENCIES_RATE_ADMIN_ERROR = 5;
    const ORDER_TO_FILL = 6;
    const MARGIN_ATTRIBUTION = 7;
    const ORDER_CANCELLATION = 8;
    const ORDER_CANCELLATION_ACCEPT = 9;
    const ORDER_CORRECTION_TO_ZERO = 10;
    const NEW_ACCOUNT_TO_APPROVE = 11;
    const CMR_COPY = 12;
    const PLATE_NUMBER_TO_COMPLETE = 13;
    const PLATE_NUMBER_TO_COMPLETE_USER = 14;
    const COPIED_ORDER_TO_CHECK = 15;

    public $message;

    public function __construct($type, $data)
    {
        switch ($type) {
            case self::CLIENT_PRICE_CHANGED:
                $this->message = new ClientPriceChangedAfterReload($data);
                break;
            case self::NOT_COMPLETED_ORDER:
                $this->message = new NotCompletedOrder($data);
                break;
            case self::NOT_COMPLETED_USER_ORDER:
                $this->message = new NotCompletedUserOrder($data);
                break;
            case self::CURRENCIES_RATE_ERROR:
                $this->message = new CurrenciesRatesLoadError();
                break;
            case self::CURRENCIES_RATE_ADMIN_ERROR:
                $this->message = new CurrenciesRatesLoadErrorAdmin();
                break;
            case self::ORDER_TO_FILL:
                $this->message = new OrderToFill($data);
                break;
            case self::MARGIN_ATTRIBUTION:
                $this->message = new MarginAttribution($data);
                break;
            case self::ORDER_CANCELLATION:
                $this->message = new OrderCancellation($data);
                break;
            case self::ORDER_CANCELLATION_ACCEPT:
                $this->message = new OrderCancellationAccept($data);
                break;
            case self::ORDER_CORRECTION_TO_ZERO:
                $this->message = new OrderCorrectionToZero($data);
                break;
            case self::NEW_ACCOUNT_TO_APPROVE:
                $this->message = new NewAccountToApprove($data);
                break;
            case self::CMR_COPY:
                $this->message = new CmrCopy($data);
                break;
            case self::PLATE_NUMBER_TO_COMPLETE:
                $this->message = new PlateNumberToComplete($data);
                break;
            case self::PLATE_NUMBER_TO_COMPLETE_USER:
                $this->message = new PlateNumberToCompleteUser($data);
                break;
            case self::COPIED_ORDER_TO_CHECK:
                $this->message = new CopiedOrderToCheck();
                break;
            default:
                throw new OutOfRangeException("Type index ($type) is out of range");
        }
    }
}
