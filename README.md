# Whalestack Payments API SDK (PHP)

Official Whalestack Payments API SDK for PHP by www.whalestack.com

Accepting cryptocurrency payments using the Whalestack API is fast, secure, and easy. After you've signed up and obtained your [API key](https://www.whalestack.com/en/api-settings), all you need to do is create a checkout or blockchain deposit address on Bitcoin, Lightning, Litecoin, Stellar, or other supported networks to get paid. You can also use the API for fiat on- and off-ramping via SWIFT or SEPA.

This SDK implements the REST API documented at https://www.whalestack.com/en/api-docs

For SDKs in different programming languages, see https://www.whalestack.com/en/api-docs#sdks

Requirements
------------
* PHP >=5.3.0
* cURL extension for PHP
* OpenSSL extension for PHP

Installation as Drop-In
-----------------------
Copy the contents of `src` into the "include path" of your project.

**Usage Client**

```php
include('WsClient.class.php');

$client = new WsClient(
    'YOUR-API-KEY',
    'YOUR-API-SECRET',
    '/var/log/whalestack.log' // an optional log file location
);
```

Get your API key and secret here: https://www.whalestack.com/en/api-settings

Guides
------

* [Using the Whalestack API](https://www.whalestack.com/en/api-docs#getting-started)
* [Building Checkouts](https://www.whalestack.com/en/api-docs#building-checkouts)
* [Authentication](https://www.whalestack.com/en/api-docs#authentication) (handled by SDK)
* [Brand Connect](https://www.whalestack.com/en/api-docs#brand-connect) (white label checkouts on your own domain)

## Wallets and Deposits

Your Whalestack account comes equipped with dedicated deposit addresses for Bitcoin, Lightning, Litecoin, Stellar, SWIFT, SEPA, and other supported networks. You can receive blockchain payments within seconds after registering. The [GET /wallets](https://www.whalestack.com/en/api-docs#get-wallets) and [GET /deposit-address](https://www.whalestack.com/en/api-docs#deposit-address) endpoints return your blockchain addresses to start receiving custom deposits.

**List Wallets and Deposit Addresses** (https://www.whalestack.com/en/api-docs#get-wallets)
```php
$response = $client->get('/wallets');
```


## Checkouts

Whalestack checkouts provide fast and convenient ways for your customers to complete payment. We built a great user experience with hosted checkouts that can be fully branded. If you're not into payment pages, you can take full control over the entire checkout process using our backend checkout APIs. Click [here](https://www.whalestack.com/en/api-docs#building-checkouts) to learn more about building checkouts.

**Create a Hosted Checkout (Payment Link)** (https://www.whalestack.com/en/api-docs#post-checkout-hosted)
```php
$response = $client->post('/checkout/hosted', array(
    'charge' => array(
        'billingCurrency' => 'EUR', // a billing currency as given by GET /currencies
        'lineItems' => array( // a list of line items included in this charge
            array(
                'description' => 'PCI Graphics Card',
                'netAmount' => 169.99, // denominated in the currency specified above
                'quantity' => 1
            )
        ),
        'discountItems' => array() // an optional list of discounts
        'shippingCostItems' => array() // any shipping costs?
        'taxItems' => array() // any taxes?
    ),
    'settlementAsset' => 'USDC:GA5ZSEJYB37JRC5AVCIA5MOP4RHTM335X2KGX3IHOJAPP5RE34K4KZVN' // your settlement asset as given by GET /assets (or ORIGIN to omit conversion) 
));
```

## Swaps And Transfers

Once funds arrive in your account, either via completed checkouts or custom deposits, you have instant access to them and the ability to swap them into other assets or transfer them to your bank account or other blockchain accounts (we recommend to always forward funds into self-custody on cold storage). The [POST /swap](https://www.whalestack.com/en/api-docs#post-swap) and [POST /transfer](https://www.whalestack.com/en/api-docs#post-transfer) endpoints will get you started on swaps and transfers.

**Swap Bitcoin to USDC** (https://www.whalestack.com/en/api-docs#post-swap)
```php
$response = $client->post('/swap', array(
    'sourceAsset' => 'BTC:GCQVEST7KIWV3KOSNDDUJKEPZLBFWKM7DUS4TCLW2VNVPCBGTDRVTEIT',
    'targetAsset' => 'USDC:GA5ZSEJYB37JRC5AVCIA5MOP4RHTM335X2KGX3IHOJAPP5RE34K4KZVN',
    'targetAmount' => 100
));
```

**Transfer USDC to your SEPA Bank** (https://www.whalestack.com/en/api-docs#post-transfer)
```php
$response = $client->post('/transfer', array(
    'network' => 'SEPA',
    'asset' => 'USDC:GA5ZSEJYB37JRC5AVCIA5MOP4RHTM335X2KGX3IHOJAPP5RE34K4KZVN',
    'amount' => 100,
    'targetAccount' => 'A unique SEPA account label as previously specified in POST /target-account'
));
```

## Supported Assets, Currencies, and Networks

**List all available Networks** (https://www.whalestack.com/en/api-docs#get-networks)
```php
$response = $client->get('/networks');
```

**List all available Assets** (https://www.whalestack.com/en/api-docs#get-assets)
```php
$response = $client->get('/assets');
```

**List all available Billing Currencies** (https://www.whalestack.com/en/api-docs#get-currencies)
```php
$response = $client->get('/currencies');
```

## Financial Reports and Accounting

We don't leave you hanging with an obscure and complicated blockchain payment trail to figure out by yourself. All transactions on Whalestack are aggregated into the Financial Reports section of your account and can even be associated with counter-parties, such as customers and beneficiaries. We provide CSV reports, charts, and beautiful analytics for all your in-house accounting needs.

Please inspect https://www.whalestack.com/en/api-docs for detailed API documentation or email us at service [at] whalestack.com if you have questions.

Support and Feedback
--------------------
We'd love to hear your feedback. If you have specific problems or bugs with this SDK, please file an issue on GitHub. For general feedback and support requests please email service [at] whalestack.com.

Contributing
------------

1. Fork it ( https://github.com/whalestackhq/php-sdk/fork )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create a new Pull Request
