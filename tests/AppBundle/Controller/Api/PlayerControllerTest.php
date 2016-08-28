<?php

namespace AppBundle\Tests\ControllerAPI;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Test\ApiTestCase;

class PlayerControllerTest extends ApiTestCase
{
    /**
     * @test
     */
    public function postAValidPlayerShouldCreateANewPlayerEntity()
    {
        $data = array(
            'name' => 'ACME'
        );

        $response = $this->client->post('/players', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($response->getHeader('Location'), '/players/1');
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'id',
            'name'
        ));
        $this->asserter()->assertResponsePropertyEquals($response, 'id', 1);
        $this->asserter()->assertResponsePropertyEquals($response, 'name', 'ACME');
    }

    /**
     * @test
     */
    public function getPlayerShouldRetrieveASinglePlayer()
    {
        $this->createPlayers(['ACME']);

        $response = $this->client->get('/players/1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'id',
            'name'
        ));
        $this->asserter()->assertResponsePropertyEquals($response, 'id', 1);
        $this->asserter()->assertResponsePropertyEquals($response, 'name', 'ACME');
    }

    /**
     * @test
     */
    public function getPlayersShouldRetrieveACollectionOfAllPlayers()
    {
        $this->createPlayers(['ACME', 'INC.']);

        $response = $this->client->get('/players');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyIsArray($response, 'players');
        $this->asserter()->assertResponsePropertyCount($response, 'players', 2);
    }
}
