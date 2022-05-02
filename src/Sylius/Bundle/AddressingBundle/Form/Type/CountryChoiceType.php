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

namespace Sylius\Bundle\AddressingBundle\Form\Type;

use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CountryChoiceType extends AbstractType
{
    /** @var RepositoryInterface */
    private $countryRepository;

    /**
     * @var TranslatorInterface|null
     */
    private $translator = null;

    public function __construct(RepositoryInterface $countryRepository, $translator = null)
    {
        $this->countryRepository = $countryRepository;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choice_filter' => null,
                'choices' => function (Options $options): iterable {
                    if (null === $options['enabled']) {
                        $countries = $this->countryRepository->findAll();
                    } else {
                        $countries = $this->countryRepository->findBy(['enabled' => $options['enabled']]);
                    }

                    $groupedCountries = [];
                    /** @var CountryInterface $country */
                    foreach ($countries as $country) {
                        $groupName = $country->isEnabled() ? 'Enabled' : 'Disabled';

                        if (null !== $this->translator) {
                            $groupName = $this->translator->trans(
                                $country->isEnabled() ? 'sylius.ui.enabled' : 'sylius.ui.disabled'
                            );
                        }

                        if (!array_key_exists($groupName, $groupedCountries)) {
                            $groupedCountries[$groupName] = [];
                        }

                        $groupedCountries[$groupName][] = $country;
                    }

                    return $groupedCountries;
                },
                'choice_value' => 'code',
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'enabled' => true,
                'label' => 'sylius.form.address.country',
                'placeholder' => 'sylius.form.country.select',
            ])
            ->setAllowedTypes('choice_filter', ['null', 'callable'])
            ->setNormalizer('choices', static function (Options $options, array $groupedCountries): array {
                if ($options['choice_filter']) {
                    foreach ($groupedCountries as &$countries) {
                        $countries = array_filter($countries, $options['choice_filter']);
                    }
                }

                foreach ($groupedCountries as &$countries) {
                    usort($countries, static function (CountryInterface $firstCountry, CountryInterface $secondCountry): int {
                        return $firstCountry->getName() <=> $secondCountry->getName();
                    });
                }

                return $groupedCountries;
            })
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_country_choice';
    }
}
