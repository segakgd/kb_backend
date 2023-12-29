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

    // todo можно добавить поле исключение (по типу except = кроме) чтоб не плодить костыли в тестах
    public function assertResponse(string $response, array $responseCorrect, array $except = ['id']): void
    {
        $response = json_decode($response, true);

        if (empty($response) || empty($responseCorrect)){
            throw new \PHPUnit\Util\Exception('Нету данных для сравнения');
        }

        $this->assertResponseItems($response, $responseCorrect);
    }

    private function assertResponseItems(array $response, mixed $responseCorrect): void
    {
        foreach ($response as $key => $responseItem){
            if ($key === 'id'){
                continue;
            }

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

            $this->assertEquals($responseItem, $responseCorrect[$key], 'Ошибка в ключе: ' . $key);
        }
    }
}
