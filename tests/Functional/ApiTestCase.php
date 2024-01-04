<?php

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    /**
     * @throws Exception
     */
    public function getEntityManager(): EntityManagerInterface
    {
        if (!$this->entityManager){
            $container = self::getContainer();
            $this->entityManager = $container->get('doctrine.orm.entity_manager');
        }

        return $this->entityManager;
    }

    public function assertResponse(array $response, array $responseCorrect, array $except = ['id']): void
    {
        if (empty($response) || empty($responseCorrect)){
            throw new \PHPUnit\Util\Exception('Нету данных для сравнения');
        }

        $this->assertResponseItems($response, $responseCorrect, $except);
    }

    private function assertResponseItems(array $response, mixed $responseCorrect, array $except = []): void
    {
        foreach ($response as $key => $responseItem){
            if (in_array($key, $except)){
                continue;
            }

            if (!key_exists($key, $responseCorrect)){
                throw new \PHPUnit\Util\Exception('Неверный ключ ' . $key . ' в входящем массиве');
            }

            if (is_array($responseItem)){
                $this->assertResponseItems($responseItem, $responseCorrect[$key], $except);

                continue;
            }

            if (is_string($responseItem) && strtotime($responseItem)){
                continue;
            }

            $this->assertEquals($responseItem, $responseCorrect[$key], 'Ошибка в ключе: ' . $key);
        }
    }
}
