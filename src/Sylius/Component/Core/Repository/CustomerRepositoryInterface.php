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

namespace Sylius\Component\Core\Repository;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @template T of CustomerInterface
 * @extends RepositoryInterface<T>
 */
interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function countCustomers(): int;

    public function countCustomersInPeriod(\DateTimeInterface $startDate, \DateTimeInterface $endDate): int;

    /**
     * @return array|CustomerInterface[]
     */
    public function findLatest(int $count): array;
}
