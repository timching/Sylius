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

namespace Sylius\Bundle\ApiBundle\StateProcessor\Admin\Zone;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Sylius\Component\Addressing\Checker\ZoneDeletionCheckerInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Exception\ResourceDeleteException;
use Webmozart\Assert\Assert;

/** @implements ProcessorInterface<ZoneInterface> */
final readonly class RemoveProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $removeProcessor,
        private ZoneDeletionCheckerInterface $zoneDeletionChecker,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        Assert::isInstanceOf($data, ZoneInterface::class);
        Assert::isInstanceOf($operation, DeleteOperationInterface::class);

        if (!$this->zoneDeletionChecker->isDeletable($data)) {
            throw new ResourceDeleteException(message: 'Cannot delete, the zone is in use.');
        }

        $this->removeProcessor->process($data, $operation, $uriVariables, $context);
    }
}
