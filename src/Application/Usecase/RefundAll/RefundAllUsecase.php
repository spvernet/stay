<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:14
 */

namespace App\Application\Usecase\RefundAll;

use App\Application\Service\RefundService;
use App\Domain\Core\AbstractOutput;
use App\Domain\Manager\RepositoryManagerInterface;
use App\Infrastructure\Output\RefundOutput;
use Psr\Log\LoggerInterface;

class RefundAllUsecase
{
    private $command;
    private $service;
    private $repository;
    private $logger;
    private $output;

    public function __construct(
        RefundAllRequest $command,
        RefundService $service,
        RepositoryManagerInterface $repository,
        LoggerInterface    $logger,
        RefundOutput $output)
    {
        $this->command = $command;
        $this->service = $service;
        $this->repository = $repository;
        $this->logger = $logger;
        $this->output = $output;

    }

    public function execute() {

        if (!$this->command->isValid()) {
            $this->logger->error('empty field', ['refund.validation']);
            $this->output->addError('field empty', 'refund.validation', AbstractOutput::CODE_BAD_REQUEST);
            return $this->output->execute();
        }

        $booking= $this->repository->getBookings($this->command->getHash());

        $total_already_paid = 0;
        $tickets            = $this->booking->tickets->toArray();

        foreach ($tickets as $ticket) {
            if (!empty($ticket['tpv_reference']) && "success" == $ticket['payment_status']) {
                $total_already_paid += $ticket['total'];
            }
        }

         $refunded = $this->service->doRefund($booking, $total_already_paid, $this->command->getReason());

        return $this->output->execute($refunded);
    }

}