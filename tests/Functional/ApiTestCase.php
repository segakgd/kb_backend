<?php

namespace App\Tests\Functional;

use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    private ?ObjectManager $entityManager = null;

    /**
     * @throws Exception
     */
    public function getEntityManager(): ObjectManager
    {
        if (!$this->entityManager){
            $container = self::getContainer();
            $this->entityManager = $container->get('doctrine.orm.entity_manager');
        }

        return $this->entityManager;
    }

    public function assertResponse(string $response, array $responseCorrect): void
    {
        $response = json_decode($response, true);

        $this->assertResponseItems($response, $responseCorrect);
    }

    private function assertResponseItems(array $response, mixed $responseCorrect): void
    {
        foreach ($response as $key => $responseItem){
            if (!key_exists($key, $responseCorrect)){
                throw new \PHPUnit\Util\Exception('Неверный ключ ' . $key . ' в входящем массиве');
            }

            if (is_array($responseItem)){
                $this->assertResponseItems($responseItem, $responseCorrect[$key]);

                continue;
            }

            if (is_string($responseItem) && strtotime($responseItem)){
                continue;
            }

            $this->assertEquals($responseItem, $responseCorrect[$key]);
        }
    }
}
