<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:34
 */

namespace App\Application\Service;

class RefundService
{

    public function doRefund(Bookings $booking, $amount, $reason = "")
    {
        $this->booking = $booking;

        if (0 == $amount) {
            throw new \InvalidArgumentException('Cannot refund an amount of 0.0');
        }

        $this->amount = $amount;
        $this->reason = $reason;

        $this->refund($this->amount);
    }


    private function refund($pending_refund_amount)
    {
        foreach ($this->booking->tickets as $ticket) {

            $pending_refund_amount = $this->refundTicket($ticket, $pending_refund_amount);

            if ($pending_refund_amount <= 0) {
                break;
            }
        }
    }

    public function applyRefund(Tickets $ticket, $amount)
    {
        /**
         * This class does the refund. Returns the amount refunded for the ticket.
         * The amount refunded may be 0 if the ticket has been refunded already.
         *
         * }*/
    }


    /** PAY ATTENTION: SHOULD BE DOMAIN SERVICE*/
    private function refundTicket(Tickets $ticket, $pending_refund_amount)
    {
        $refunded = $this->applyRefund($ticket, $pending_refund_amount);
        $pending_refund_amount = $pending_refund_amount - $refunded;

        return $pending_refund_amount;
    }



}