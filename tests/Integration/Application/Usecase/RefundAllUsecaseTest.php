<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:58
 */


namespace App\Tests\Integration\Application\Usecase;

use App\Domain\Core\AbstractOutput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RefundAllUsecaseTest extends WebTestCase
{

    public function testRefundAllOK() {

        $client = $this->createClient();

        $data = array(
            'id' => '1',
            'internal_user' => '123456789',
            'reason' => 'my reason'
        );


        $client->request(
            'POST',
            '/refund/all',
            array(
                'Content-type' => 'application/json; charset=utf-8',
            ),
            array(),
            array(),
            json_encode($data)
        );

        $result= json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(AbstractOutput::CODE_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals('', $result['data']);
    }

    public function testRefundAllKO() {


        $client = $this->createClient();

        $data = array(
            'id' => '1',
            'internal_user' => '123456789'
        );


        $client->request(
            'POST',
            '/refund/all',
            array(
                'Content-type' => 'application/json; charset=utf-8',
            ),
            array(),
            array(),
            json_encode($data)
        );

        $result= json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(AbstractOutput::CODE_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertEquals('field empty', $result['metadata'][0]['message']);
    }

}