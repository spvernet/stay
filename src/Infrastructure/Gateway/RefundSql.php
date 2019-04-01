<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:28
 */


namespace App\Infrastructure\Gateway;

class RefundSql implements RepositoryManagerInterface
{


    public function getBookings(string $hash)
    {
       // return Bookings::with("tickets")->whereHash($hash)->first();
    }
}