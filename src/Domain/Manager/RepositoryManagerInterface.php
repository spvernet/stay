<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:29
 */


namespace App\Domain\Manager;

interface RepositoryManagerInterface
{
    public function getBookings(string $hash);
}