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
}