<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:15
 */

namespace App\Application\Usecase\RefundAll;

use App\Domain\Exception\EmptyException;

class RefundAllRequest
{

    private $id;

    private $internalUser;

    private $reason;

    public function __construct(string $id, string $internalUser, string $reason)
    {
        $this->id = $id;
        $this->internalUser = $internalUser;
        $this->reason = $reason;
    }

    public function isValid() {
        if (empty($this->id)||empty($this->internalUser)||empty($this->reason)){
            throw new EmptyException("one field missing");
        }

        return true;
    }
    public function getHash(){
        return $this->id;
    }

    public function getReason(){
        return $this->reason;
    }

}