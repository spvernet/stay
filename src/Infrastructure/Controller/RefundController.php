<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 18:35
 */

namespace App\Infrastructure\Controller;

use App\Application\Service\RefundService;
use App\Application\Usecase\RefundAll\RefundAllRequest;
use App\Application\Usecase\RefundAll\RefundAllUsecase;
use App\Application\Usecase\RefundPartial\RefundPartialRequest;
use App\Application\Usecase\RefundPartial\RefundPartialUsecase;
use App\Domain\Manager\RepositoryManagerInterface;
use App\Infrastructure\Output\RefundOutput;
use Psr\Log\LoggerInterface;
use Symfony\Component\BrowserKit\Request;

class RefundController
{

    const TICKET_DESCRIPTION = "Booking refunded";

    public $request;
    public $booking;
    public $internal_user;

    public function refundAll(Request $request, LoggerInterface $logger ){

        $req = json_decode($request->getContent(), true);
        $id = $req['id'] ?? "";
        $internalUser = $req['internal_user'] ?? "";
        $reason = $req['reason'] ?? "";

        $command = new RefundAllRequest($id, $internalUser, $reason);
        $usecase = new RefundAllUsecase(
            $command,
            new RefundService(),
            new RefundSql(),
            $logger,
            new RefundOutput()
        );

        return $usecase->execute();
    }

    public function refundPartial(Request $request, LoggerInterface $logger )
    {
        $req = json_decode($request->getContent(), true);
        $id = $req['id'] ?? "";
        $internalUser = $req['internal_user'] ?? "";
        $amount = $req['internal_user'] ?? "";
        $reason = $req['reason'] ?? "";

        $command = new RefundPartialRequest($id, $internalUser, $amount, $reason);
        $usecase = new RefundPartialUsecase(
            $command,
            new RefundService(),
            new RefundSql(),
            $logger,
            new RefundOutput()
        );

        return $usecase->execute();

    }



    /*public function refundAll(Request $request)
    {
        $this->request = $request;

        $hash                = $request->get('id');
        $this->internal_user = $request->get('internal_user');

        $this->booking = Bookings::with("tickets")->whereHash($hash)->first();
        if (empty($this->booking)) {
            PaymentException::throwBecauseOf("Booking with hash {$hash} not found!");
        }

        $reason = $this->request->get('reason', '');

        $total_already_paid = 0;
        $tickets            = $this->booking->tickets->toArray();
        foreach ($tickets as $ticket) {
            if (!empty($ticket['tpv_reference']) && "success" == $ticket['payment_status']) {
                $total_already_paid += $ticket['total'];
            }
        }

        $this->doRefund($this->booking, $total_already_paid, $reason);
    }

    public function refundPartial(Request $request)
    {
        $this->request = $request;

        $hash                = $request->get('id');
        $this->internal_user = $request->get('internal_user');

        $this->booking = Bookings::query()->loadRelation('tickets')->where('hash', '=', $hash)->first();
        if (empty($this->booking)) {
            PaymentException::throwBecauseOf("Booking with hash {$hash} not found!");
        }

        $amount = (float)$this->request->get('amount');
        $reason = $this->request->get('reason', '');

        $this->doRefund($this->booking, $amount, $reason);
    }

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

    public function refund($pending_refund_amount)
    {
        foreach ($this->booking->tickets as $ticket) {

            $pending_refund_amount = $this->refundTicket($ticket, $pending_refund_amount);

            if ($pending_refund_amount <= 0) {
                break;
            }
        }
    }

    protected function refundTicket(Tickets $ticket, $pending_refund_amount)
    {
        $refunded = $this->applyRefund($ticket, $pending_refund_amount);
        $pending_refund_amount = $pending_refund_amount - $refunded;

        return $pending_refund_amount;
    }

    protected function getTicketDescription()
    {
        if (empty($this->reason)) {
            return self::TICKET_DESCRIPTION;
        }
        return sprintf('%s: %s', self::TICKET_DESCRIPTION, $this->reason);
    }

    public function applyRefund(Tickets $ticket, $amount)
    {
        /**
         * This class does the refund. Returns the amount refunded for the ticket.
         * The amount refunded may be 0 if the ticket has been refunded already.
         *
    }*/
}