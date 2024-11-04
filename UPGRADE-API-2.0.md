# UPGRADE FROM `1.14` TO `2.0`

## Codebase

* The following classes were removed:
    * `Sylius\Bundle\ApiBundle\EventListener\PostgreSQLDriverExceptionListener`

* The following services were removed:
    * `sylius.listener.api_postgresql_driver_exception_listener`

* Non-prefix serialization groups in Sylius resources have been removed.
   If you have extended any of them, you must prefix them with `sylius:`, for example:

    ```diff
    - #[Groups(['admin:product:index'])]
    + #[Groups(['sylius:admin:product:index'])]
    ```

* API Platform has dropped `DataProviders` and `DataPersisters` in favor of `Providers` and `Processors`, respectively.
  Due to this change, Sylius custom `DataProviders` and `DataPersisters` have been adapted to the new API Platform interfaces
  and their namespaced have been changed to `StateProvider` and `StateProcessor` respectively:
- `Sylius\Bundle\ApiBundle\DataPersister\*DataPersister` => `Sylius\Bundle\ApiBundle\StateProcessor\*Processor`
- `Sylius\Bundle\ApiBundle\DataProvider\*DataProvider` => `Sylius\Bundle\ApiBundle\StateProvider\*Provider`

* API Platform has also dropped `DataTransformers` in favor of which `some of them` have been refactored into `SerializerContextBuilders` as follows:
- `Sylius\Bundle\ApiBundle\DataTransformer\ChannelCodeAwareInputCommandDataTransformer` => `Sylius\Bundle\ApiBundle\SerializerContextBuilder\ChannelCodeAwareContextBuilder`
- `Sylius\Bundle\ApiBundle\DataTransformer\LocaleCodeAwareInputCommandDataTransformer` => `Sylius\Bundle\ApiBundle\SerializerContextBuilder\LocaleCodeAwareContextBuilder`
- `Sylius\Bundle\ApiBundle\DataTransformer\LoggedInCustomerEmailIfNotSetAwareCommandDataTransformer` => `Sylius\Bundle\ApiBundle\SerializerContextBuilder\LoggedInCustomerEmailIfNotSetAwareContextBuilder`
- `Sylius\Bundle\ApiBundle\DataTransformer\LoggedInShopUserIdAwareCommandDataTransformer` => `Sylius\Bundle\ApiBundle\SerializerContextBuilder\ChannelCodeAwareContextBuilder`

* The constructor of `Sylius\Bundle\ApiBundle\Command\Account\RegisterShopUser` has been changed:
```php
    public function __construct(
    -   public string $firstName,
    -   public string $lastName,
    -   public string $email,
    -   public string $password,
    -   public bool $subscribedToNewsletter = false,
    +   protected string $firstName,
    +   protected string $lastName,
    +   protected string $email,
    +   protected string $password,
    +   protected ?string $channelCode,
    +   protected ?string $localeCode,
    +   protected bool $subscribedToNewsletter = false,
    ) {
    }
```

* The constructor of `Sylius\Bundle\ApiBundle\Command\Account\RequestResetPasswordToken` has been changed:
```php
    public function __construct(
    -   public string $email,
    +   protected string $email,
    +   protected ?string $channelCode,
    +   protected ?string $localeCode,
    ) {
    }
```

* The constructor of `Sylius\Bundle\ApiBundle\Command\SendContactRequest` has been changed:
```php
    public function __construct(
    -   private ?string $email = null,
    -   private ?string $message = null,
    +   protected ?string $channelCode,
    +   protected ?string $localeCode,
    +   protected ?string $email = null,
    +   protected ?string $message = null,
    ) {
    }
```

* The constructor of `Sylius\Bundle\ApiBundle\Command\Account\VerifyShopUser` has been changed:
```php
    public function __construct(
    -   public string $token,
    -   private ?string $localeCode = null,
    -   private ?string $channelCode = null,
    +   protected string $token,
    +   protected string $channelCode,
    +   protected string $localeCode,
    ) {
    }
```

* The constructor of `Sylius\Bundle\ApiBundle\Command\Account\ChangeShopUserPassword` has been changed:
```php
    public function __construct(
    -   public ?string $newPassword,
    -   public ?string $confirmNewPassword,
    -   public ?string $currentPassword,
    +   protected mixed $shopUserId,
    +   protected string $newPassword,
    +   protected string $confirmNewPassword,
    +   protected string $currentPassword,
    ) {
    }
```

* The constructor of `Sylius\Bundle\ApiBundle\Command\Account\RequestShopUserVerification` has been created:
```php
    public function __construct(
    +   protected string|int|null $shopUserId,
    +   protected ?string $channelCode,
    +   protected ?string $localeCode,
    ) {
    }
```

All the `setter` methods have been removed from the commands above and also there are some new `getter` methods accordingly to arguments visibility changes.

* The parameter type and order of the `Sylius\Bundle\ApiBundle\Controller\UploadAvatarImageAction::__construct` has been changed:
```php
    public function __construct(
        private FactoryInterface $avatarImageFactory,
        private AvatarImageRepositoryInterface $avatarImageRepository,
    -   private ImageUploaderInterface $imageUploader,
    -   private IriConverterInterface $iriConverter,
    +   private RepositoryInterface $adminUserRepository,
    +   private ImageUploaderInterface $imageUploader,
    )
```

* The `getCurrentPrefix` method has been removed from the `Sylius\Bundle\ApiBundle\Provider\PathPrefixProviderInterface`.

* The `Sylius\Bundle\ApiBundle\Provider\PathPrefixProvider` constructor has been changed due to refactor. 
  Now, we provide the list of possible prefixes that we check in this service. This list can be set under 
  the parameter: `sylius.api_path_prefixes`. 

    ```diff
        public function __construct(
    -       private UserContextInterface $userContext,
            private string $apiRoute,
    +       private array $pathPrefixes,
        ) {
            ...
        }
    ```

* The signature of method `applyToCollection` of the following classes has been changed:
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\AcceptedProductReviewsExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\AddressesExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\AvailableProductAssociationsInProductCollectionExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\CountryCollectionExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\CurrencyCollectionExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\EnabledProductVariantsExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\HideArchivedShippingMethodExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\LocaleCollectionExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\OrdersByChannelExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\OrdersByLoggedInUserExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\ProductsByChannelAndLocaleCodeExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\ProductsByTaxonExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\ProductsWithEnableFlagExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\ProductsWithEnableFlagExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\RestrictingFilterEagerLoadingExtension`
    * `Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension\TaxonCollectionExtension`

```php
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
    -   string $operationName = null,
    +   \ApiPlatform\Metadata\Operation $operation = null,
        array $context = [],
    ): void;
```

* The `Sylius\Bundle\ApiBundle\OpenApi\Documentation\PathHiderDocumentationModifier` class and service have been removed.
  The `sylius.api.paths_to_hide` parameter used in this class has also been removed. We recommend defining endpoints
  with the `ApiPlatform\Metadata\NotExposed` class to hide specific operations.

* The following parameters have been renamed:
    
  | Old parameter                                | New parameter                            |
  |----------------------------------------------|------------------------------------------|
  | `sylius.security.new_api_route`              | `sylius.security.api_route`              |
  | `sylius.security.new_api_regex`              | `sylius.security.api_regex`              |
  | `sylius.security.new_api_admin_route`        | `sylius.security.api_admin_route`        |
  | `sylius.security.new_api_admin_regex`        | `sylius.security.api_admin_regex`        |
  | `sylius.security.new_api_shop_route`         | `sylius.security.api_shop_route`         |
  | `sylius.security.new_api_shop_regex`         | `sylius.security.api_shop_regex`         |
  | `sylius.security.new_api_user_account_route` | `sylius.security.api_shop_account_route` |
  | `sylius.security.new_api_user_account_regex` | `sylius.security.api_shop_account_regex` |

1. The following configuration parameters have been removed:

    - `sylius_api.legacy_error_handling`
    - `sylius_api.serialization_groups.skip_adding_read_group`
    - `sylius_api.serialization_groups.skip_adding_index_and_show_groups`

## Resource configuration changes

### Updated API Routes

* AvatarImage

- `'GET' - /api/v2/admin/avatar-images/{id}` → `'GET' - /api/v2/admin/administrators/{id}/avatar-image`
- `'POST' - /api/v2/admin/avatar-images` → `'POST' - /api/v2/admin/administrators/{id}/avatar-image`
- `'DELETE' - /api/v2/admin/avatar-images/{id}` → `'DELETE' - /api/v2/admin/administrators/{id}/avatar-image`

* ShopUser, CustomerPassword and CustomerVerification

- `'POST' - /api/v2/shop/reset-password-requests` → `'POST' - /api/v2/shop/reset-password`
- `'PATCH' - /api/v2/shop/reset-password-requests/{resetPasswordToken}` → `'PATCH' - /api/v2/shop/reset-password/{resetPasswordToken}`
- `'POST' - /api/v2/shop/account-verification-requests` → `'POST' - /api/v2/shop/verify-shop-user`
- `'PATCH' - /api/v2/shop/account-verification-requests/{token}` → `'PATCH' - /api/v2/shop/verify-shop-user/{token}`

* AdminUserPassword

- `'POST' - /api/v2/admin/reset-password-requests` → `'POST' - /api/v2/admin/reset-password`
- `'PATCH' - /api/v2/admin/reset-password-requests/{resetPasswordToken}` → `'PATCH' - /api/v2/admin/reset-password/{resetPasswordToken}`

* CatalogPromotion

- `'GET' - /api/v2/admin/catalog-promotion-actions/{id}` → `'GET' - /api/v2/admin/catalog-promotions/{code}/actions/{id}`
- `'GET' - /api/v2/admin/catalog-promotion-scopes/{id}` → `'GET' - /api/v2/admin/catalog-promotions/{code}/scopes/{id}`

* Translation

- `'GET' - /api/v2/admin/[resource]-translations/{id}` → `'GET' - /api/v2/admin/[resource]/{code}/translations/{localeCode}`

* ChannelPricing

- `'GET' - /api/v2/admin/channel-pricings/{id}` → `'GET' - /api/v2/admin/product-variants/{code}/pricing/{id}`

* Order

- `'GET' - /api/v2/admin/order-items/{id}` → `'GET' - /api/v2/admin/orders/{orderToken}/items/{id}`

* Payment

- `'GET' - /api/v2/shop/payments/{id}` → `'GET' - /api/v2/shop/orders/{orderToken}/payments/{id}`

* Product

- `'GET' - /api/v2/admin/product-images/{id}` → `'GET' - /api/v2/admin/products/{code}/images/{id}`
- `'GET' - /api/v2/admin/products-images` → `'GET' - /api/v2/admin/products/{code}/images`
- `'GET' - /api/v2/shop/product-images/{id}` → `'GET' - /api/v2/shop/products/{code}/images/{id}`
- `'GET' - /api/v2/admin/product-option-values/{code}` → `'GET' - /api/v2/admin/product-options/{optionCode}/values/{code}`
- `'GET' - /api/v2/shop/product-option-values/{code}` → `'GET' - /api/v2/shop/product-options/{optionCode}/values/{code}`

* Promotion

- `'GET' - /api/v2/admin/promotion-actions/{id}` → `'GET' - /api/v2/admin/promotions/{code}/actions/{id}`
- `'GET' - /api/v2/admin/promotion-coupons/{id}` → `'GET' - /api/v2/admin/promotions/{code}/coupons/{id}`
- `'POST' - /api/v2/admin/promotion-coupons/generate` → `'POST' - /api/v2/admin/promotions/{promotionCode}/coupons/generate`
- `'PUT' - /api/v2/admin/promotion-coupons/{code}` → `'PUT' - /api/v2/admin/promotions/{promotionCode}/coupons/{couponCode}`
- `'DELETE' - /api/v2/admin/promotion-coupons/{code}` → `'DELETE' - /api/v2/admin/promotions/{promotionCode}/coupons/{couponCode}`
- `'GET' - /api/v2/admin/promotion-rules/{id}` → `'GET' - /api/v2/admin/promotions/{code}/rules/{id}`

* Provinces

- `'GET' - /api/v2/admin/provinces/{code}` → `'GET' - /api/v2/admin/countries/{countryCode}/provinces/{provinceCode}`
- `'PUT' - /api/v2/admin/provinces/{code}` → `'PUT' - /api/v2/admin/countries/{countryCode}/provinces/{provinceCode}`
- `'GET' - /api/v2/shop/provinces/{code}` → `'GET' - /api/v2/shop/countries/{countryCode}/provinces/{provinceCode}`

* Shipment

- `'GET' - /api/v2/shop/shipments/{id}` → `'GET' - /api/v2/shop/orders/{orderToken}/shipments/{id}`

* Taxon

- `'GET' - /api/v2/admin/taxon-images/{id}` → `'GET' - /api/v2/admin/taxons/{code}/images/{id}`
- `'POST' - /api/v2/admin/taxon-images` → `'POST' - /api/v2//admin/taxons/{code}/images`
- `'PUT' - /api/v2/admin/taxon-images/{id}` → `'PUT' - /api/v2/admin/taxons/{code}/images/{id}`
- `'DELETE' - /api/v2/admin/taxon-images/{id}` → `'DELETE' - /api/v2/admin/taxons/{code}/images/{id}`
- `'GET' - /api/v2/shop/taxon-images/{id}` → `'GET' - /api/v2/shop/taxons/{code}/images/{id}`

### Other Resource Changes

1. **Adjustment**
    - The shop item endpoint is no longer exposed.

1. **CustomerVerification**
    - `Sylius\Bundle\ApiBundle\Command\Account\VerifyCustomerAccount` command has been renamed to `Sylius\Bundle\ApiBundle\Command\Account\VerifyShopUser`.
    - `Sylius\Bundle\ApiBundle\Command\Account\ResendVerificationEmail` command has been renamed to `Sylius\Bundle\ApiBundle\Command\Account\RequestShopUserVerification`.

1. **GatewayConfig**
    - The resource is no longer exposed.

1. **Translation**
    - All translation resources are no longer exposed.

1. **ChannelPriceHistoryConfig**
    - The resource configuration has been removed, as it is now handled by the `Channel` resource.

1. **ShopBillingData**
    - The resource configuration has been removed, as it is now handled by the `Channel` resource.

1. **ZoneMember**
    - The resource configuration has been removed, as it is now handled by the `Zone` resource.

1. **ProductOptionValueTranslation**
    - There has been added a new resource configuration for the `ProductOptionValueTranslation` resource.
