---
layout:
  title:
    visible: true
  description:
    visible: false
  tableOfContents:
    visible: true
  outline:
    visible: true
  pagination:
    visible: true
---

# Zones

**Zones** are a part of the [Addressing](https://docs.sylius.com/en/1.13/book/customers/addresses/addresses.html) concept.

## Zones and ZoneMembers

**Zones** consist of **ZoneMembers**. It can be any kind of zone you need - for instance, if you want to have all the EU countries in one zone, or just a few chosen countries that have the same taxation system in one zone, or you can even distinguish zones by the ZIP code ranges in the USA.

Three different types of zones are available:

* **country** zone, which consists of countries.
* **province** zone, which is constructed from provinces.
* **zone**, which is a group of other zones.

## How to add a Zone?

Let’s see how you can add a Zone to your system programmatically.

Firstly you will need a factory for zones - There is a specific one.

```php
/** @var ZoneFactoryInterface $zoneFactory */
$zoneFactory = $this->container->get('sylius.factory.zone');
```

Using the ZoneFactory create a new zone with its members. Let’s take the UK as an example.

```php
/** @var ZoneInterface $zone */
$zone = $zoneFactory->createWithMembers(['GB_ENG', 'GB_NIR', 'GB_SCT'. 'GB_WLS']);
```

Now give it a code, name, and type:

```php
$zone->setCode('GB');
$zone->setName('United Kingdom');
// available types are the type constants from the ZoneInterface
$zone->setType(ZoneInterface::TYPE_PROVINCE);
```

Finally, get the zones repository from the container and add the newly created zone to the system.

```php
/** @var RepositoryInterface $zoneRepository */
$zoneRepository = $this->container->get('sylius.repository.zone');

$zoneRepository->add($zone);
```

## Matching a Zone

Zones are not very useful alone, but they can be a part of a complex taxation/shipping or any other system. A service implementing the _ZoneMatcherInterface_ is responsible for matching the **Address** to a specific **Zone**.

```php
/** @var ZoneMatcherInterface $zoneMatcher */
$zoneMatcher = $this->get('sylius.zone_matcher');
$zone = $zoneMatcher->match($user->getAddress());
```

ZoneMatcher can also return all matching zones. (not only the most suitable one)

```php
/** @var ZoneMatcherInterface $zoneMatcher */
$zoneMatcher = $this->get('sylius.zone_matcher');
$zones = $zoneMatcher->matchAll($user->getAddress());
```

Internally, Sylius uses this service to define the shipping and billing zones of an `Order`, but you can use it for many different things and it is totally up to you.
