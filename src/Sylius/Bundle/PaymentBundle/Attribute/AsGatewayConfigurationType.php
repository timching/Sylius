<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\PaymentBundle\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsGatewayConfigurationType
{
    public const SERVICE_TAG = 'sylius.gateway_configuration_type';

    public function __construct(
        private string $type,
        private string $label,
        private int $priority = 0,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
