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
}