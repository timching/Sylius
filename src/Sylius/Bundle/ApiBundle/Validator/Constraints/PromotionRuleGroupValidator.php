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

namespace Sylius\Bundle\ApiBundle\Validator\Constraints;

use Sylius\Component\Promotion\Model\PromotionRuleInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/** @experimental */
final class PromotionRuleGroupValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PromotionRuleGroup) {
            throw new UnexpectedTypeException($constraint, PromotionRuleGroup::class);
        }

        if (!$value instanceof PromotionRuleInterface) {
            throw new UnexpectedValueException($value, PromotionRuleInterface::class);
        }

        /** @var string[] $groups */
        $groups = array_merge($constraint->groups, [$value->getType()]);
        $validator = $this->context->getValidator()->inContext($this->context);
        $validator->validate(value: $value, groups: $groups);
    }
}
