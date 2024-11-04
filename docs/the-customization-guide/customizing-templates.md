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

# Customizing Templates

Sylius provides two primary template types: **Shop** and **Admin** templates. Additionally, you can create custom templates to meet your specific needs.

## Why Customize Templates?

The main reason for modifying existing templates is to implement a unique layout that aligns with your brand. Even if you stick to Sylius's default layout, you might need minor adjustments to fulfill specific business requirements, such as adding a logo.

#### Template Customization Methods

There are three ways to customize Sylius templates:

1. **Overriding Templates**\
   This is done in the `templates/bundles` directory of your project, allowing you to modify templates entirely, the Symfony way.
2. **Using Twig Hooks**\
   This approach lets you add custom blocks without duplicating entire templates, making it ideal for plugins.
3. **Implementing Sylius Themes**\
   Themes allow different designs for multiple channels within a Sylius instance. Although it requires a few additional steps, themes offer enhanced flexibility.

## Customizing Templates via Overriding

To determine the template you need to override:

* Navigate to the desired page.
* In the Symfony toolbar, click on the route. The profiler will display the path in **Request Attributes** under `_sylius`.

**Shop Template Example: Login Page Customization**

* **Default login template**: `@SyliusShopBundle/login.html.twig`
* **Override path**: `templates/bundles/SyliusShopBundle/login.html.twig`

Copy the original template to your path and customize it as needed. Example:

```twig
{% raw %}
{% extends '@SyliusShop/layout.html.twig' %}
{% import '@SyliusUi/Macro/messages.html.twig' as messages %}

{% block content %}
<div class="ui column stackable center page grid">
    {% if last_error %}
        {{ messages.error(last_error.messageKey|trans(last_error.messageData, 'security')) }}
    {% endif %}
    <h1>This Is My Headline</h1>
    <div class="five wide column"></div>
    <form class="ui six wide column form segment" action="{{ path('sylius_shop_login_check') }}" method="post" novalidate>
        <div class="one field">
            {{ form_row(form._username, {'value': last_username|default('')}) }}
        </div>
        <div class="one field">
            {{ form_row(form._password) }}
        </div>
        <div class="one field">
            <button type="submit" class="ui fluid large primary submit button">{{ 'sylius.ui.login_button'|trans }}</button>
        </div>
    </form>
</div>
{% endblock %}
{% endraw %}
```

Clear the cache if changes aren't visible: `php bin/console cache:clear`

## Customizing Templates via Twig Hooks

<mark style="background-color:red;">#TODO by Development Team</mark>

Twig Hooks: theory

How to locate Twig Hooks?

How to use Twig Hooks for customizations?



## Customizing Templates via Sylius Themes

{% hint style="info" %}
Read more in the [Themes documentation in The Book](../the-book/frondend-and-themes.md) and [the bundle's documentation on GitHub](https://github.com/Sylius/SyliusThemeBundle/blob/master/docs/index.md).
{% endhint %}

## Global Twig variables

Each of the Twig templates in Sylius is provided with the `sylius` variable, that comes from the [ShopperContext](https://github.com/Sylius/Sylius/blob/2.0/src/Sylius/Component/Core/Context/ShopperContext.php).

The **ShopperContext** is composed of `ChannelContext`, `CurrencyContext`, `LocaleContext` and `CustomerContext`. Therefore it has access to the current channel, currency, locale, and customer.

The variables available in Twig are:

| Twig variable       | ShopperContext method name |
| ------------------- | -------------------------- |
| sylius.channel      | getChannel()               |
| sylius.currencyCode | getCurrencyCode()          |
| sylius.localeCode   | getLocaleCode()            |
| sylius.customer     | getCustomer()              |

#### How to use these Twig variables?

You can check for example what is the current channel by dumping the `sylius.channel` variable.

```twig
{{ dump(sylius.channel) }}
```

That’s it, this will dump the content of the current Channel object.
