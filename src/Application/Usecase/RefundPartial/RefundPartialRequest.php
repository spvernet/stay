<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:15
 */


namespace App\Application\Usecase\RefundPartial;

class RefundPartialRequest
{
    private $id;

    private $internalUser;

    private $reason;

    private $amount;

    public function __construct(string $id, string $internalUser, string $amount, string $reason)
    {
        $this->id = $id;
        $this->internalUser = $internalUser;
        $this->amount = $amount;
        $this->reason = $reason;
    }

    public function isValid() {
        if (empty($this->id)||empty($this->internalUser)||empty($this->reason)||empty($this->amount)){
            throw new EmptyException("one field missing");
        }
        return true;
    }
}