<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ApiBundle\Serializer;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class HydraErrorNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        /** @var NormalizerInterface&CacheableSupportsMethodInterface */
        private NormalizerInterface $decorated,
        private RequestStack $requestStack,
        private string $newApiRoute,
    ) {
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return $this->decorated->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        $path = $this->requestStack->getMainRequest()->getPathInfo();

        if (!str_starts_with($path, $this->newApiRoute)) {
            return false;
        }

        return $this->decorated->supportsNormalization($data, $format);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return $this->decorated->hasCacheableSupportsMethod();
    }
}
