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

namespace Sylius\Component\Payment\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

class Payment implements PaymentInterface
{
    use TimestampableTrait;

    /** @var mixed */
    protected $id;

    /** @var PaymentMethodInterface */
    protected $method;

    /** @var string */
    protected $currencyCode;

    /** @var string|int */
    protected $amount = 0;

    /** @var string */
    protected $state = PaymentInterface::STATE_CART;

    /** @var array */
    protected $details = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMethod(): ?PaymentMethodInterface
    {
        return $this->method;
    }

    public function setMethod(?PaymentMethodInterface $method): void
    {
        $this->method = $method;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getAmount(): ?int
    {
        return (int) $this->amount;
    }

    /** @param string|int $amount (bigint in db) */
    public function setAmount($amount): void
    {
        $this->amount = (int) $amount > PHP_INT_MAX ? PHP_INT_MAX : (int) $amount;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): void
    {
        $this->details = $details;
    }
}
