# Customizing API

Sylius is using the API Platform to create all endpoints in Sylius API. API Platform allows configuring an endpoint by `yaml` and `xml` files or by annotations. In this guide, you will learn how to customize Sylius API endpoints using `xml` configuration.

## How to add an endpoint to the Sylius API?

To add a new endpoint for the `Order` resource, follow these steps:

1.  **Copy the Existing Configuration**\
    Navigate to the `Order` resource configuration located in:

    ```shell
    %kernel.project_dir%/vendor/sylius/sylius/src/Sylius/Bundle/ApiBundle/Resources/config/api_resources/
    ```

    Copy the file `Order.xml` to:

    ```sh
    %kernel.project_dir%/config/api_platform/
    ```
2.  **Add Custom Configuration**\
    In the copied `Order.xml` file in your project's `config/api_platform/` directory, add the following configuration within the `<collectionOperations>` section to define a new endpoint:

    ```xml
    <collectionOperations>
        <collectionOperation name="custom_operation">
            <attribute name="method">POST</attribute>
            <attribute name="path">/shop/orders/custom-operation</attribute>
            <attribute name="messenger">input</attribute>
            <attribute name="input">App\Command\CustomCommand</attribute>
        </collectionOperation>
    </collectionOperations>
    ```

    This configuration creates a new endpoint at `/shop/orders/custom-operation`, which triggers a custom command.

{% hint style="warning" %}
**Order Modification Restrictions**\
By default, API Platform prevents modifying orders unless they are in the `cart` state. Only specific actions are allowed for non-`GET` requests on orders. To create a custom endpoint that interacts with orders outside the `cart` state, you must **whitelist** this endpoint.

* Add your custom API route to the `sylius.api.doctrine_extension.order_shop_user_item.filter_cart.allowed_non_get_operations` parameter to allow it to modify orders in other states.
{% endhint %}

## How to remove an endpoint from the Sylius API?

If you want to remove an unnecessary endpoint, such as when offering only digital products and therefore not needing a shipping method, follow these steps:

1. **Edit the Configuration**\
   Open the `Order.xml` file in your `config/api_platform/` directory.
2.  **Remove the Endpoint Configuration**\
    Locate and delete the configuration block for the endpoint you want to remove. For example, to remove the shipping method selection endpoint:

    ```xml
    <!-- delete this configuration -->
    <itemOperation name="shop_select_shipping_method">
        <!-- ... -->
    </itemOperation>
    ```

This removes the specified endpoint from your API, effectively disabling it for your store.

{% hint style="info" %}
Learn more about endpoint operations [here](https://api-platform.com/docs/core/operations/).
{% endhint %}

