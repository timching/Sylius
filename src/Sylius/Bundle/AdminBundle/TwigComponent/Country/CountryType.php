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

namespace Sylius\Bundle\AdminBundle\TwigComponent\Country;

use Sylius\Bundle\AdminBundle\TwigComponent\HookableComponentTrait;
use Sylius\Component\Addressing\Model\Country;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent(name: 'SyliusAdmin.Country.CountryType', template: '@SyliusAdmin/Country/_form.html.twig')]
final class CountryType
{
    use DefaultActionTrait;
    use HookableComponentTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'formData')]
    public ?Country $country = null;

    #[LiveProp]
    public bool $isUpdate = false;

    /** @param class-string $formClass */
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly string $formClass,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->formFactory->create($this->formClass, $this->country);
    }
}
