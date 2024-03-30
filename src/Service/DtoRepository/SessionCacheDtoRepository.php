<?php

namespace App\Service\DtoRepository;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Visitor\VisitorSession;
use Symfony\Component\Serializer\SerializerInterface;

class SessionCacheDtoRepository
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function get(VisitorSession $visitorSession): CacheDto
    {
        $cache = $visitorSession->getCache();

        /** @var CacheDto $cacheDto */
        return $this->serializer->denormalize($cache, CacheDto::class);
    }

    public function save(VisitorSession $visitorSession, CacheDto $cacheDto): void
    {
        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);
    }
}
