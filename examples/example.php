#!/usr/bin/php
<?
include('../src/CQMerchantClient.class.php');

/**
 * This file contains examples on how to interact with the COINQVEST Merchant API.
 * All endpoints of the API are documented here: https://www.coinqvest.com/en/api-docs
 */

/**
 * Let's create a COINQVEST Merchant API client
 * The constructor takes your API Key, API Secret and an optional log file location as parameters
 * You API Key and Secret can be obtained here: https://www.coinqvest.com/en/api-settings
 */
$client = new CQMerchantClient('YOUR-API-KEY', 'YOUR-API-SECRET', '/var/log/coinqvest.log');

/**
 * Let's invoke a request to GET /auth-test (https://www.coinqvest.com/en/api-docs#get-auth-test) to see if everything worked
 */
$response = $client->get('/auth-test');

/**
 * The API should return an HTTP status code of 200 if the request was successfully processed, let's have a look.
 */
echo "Status Code: " . $response->httpStatusCode . "\n";
echo "Response Body: " . $response->responseBody . "\n";

/**
 * Let's check our USD wallet balance using GET /wallet (https://www.coinqvest.com/en/api-docs#get-wallet)
 */
$response = $client->get('/wallet', array('assetCode' => 'USD'));
echo "Status Code: " . $response->httpStatusCode . "\n";
echo "Response Body: " . $response->responseBody . "\n";

/**
 * Let's create a checkout and get paid in two easy steps!
 *
 * 1) It's good practice to associate payments with a customer, let's create one!
 * Invoke POST /customer (https://www.coinqvest.com/en/api-docs#post-customer) to create a new customer object.
 * Tip: At a minimum a customer needs an email address, but it's better to provide a full billing address for invoices.
 */
$response = $client->post('/customer', array(
    'customer' => array(
        'email' => 'john@doe.com',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'company' => 'ACME Inc.',
        'adr1' => '810 Beach St',
        'adr2' => 'Finance Department',
        'zip' => 'CA 94133',
        'city' => 'San Francisco',
        'countrycode' => 'US'
    )
));

echo "Status Code: " . $response->httpStatusCode . "\n";
echo "Response Body: " . $response->responseBody . "\n";

if ($response->httpStatusCode != 200) {
    // something went wrong, let's abort and debug by looking at our log file specified above in the client.
    echo "Could not create customer, please check the logs.";
    exit;
}

// the customer was created
$data = json_decode($response->responseBody, true);
// $data now contains an object as specified in the success response here: https://www.coinqvest.com/en/api-docs#post-customer
// let's extract the customer id to use it in our checkout below
$customerId = $data['customerId'];

/**
 * 2) We have a customer. Let's create a checkout for him/her.
 * We're creating a hosted checkout, which will provide a payment interface hosted on COINQVEST servers
 */

$response = $client->post('/checkout/hosted', array(
    'charge' => array(
        'customerId' => $customerId, // associates this charge with a customer
        'currency' => 'USD', // specifies the billing currency
        'lineItems' => array( // a list of line items included in this charge
            array(
                'description' => 'T-Shirt',
                'netAmount' => 10, // denominated in the currency specified above
                'quantity' => 1
            )
        ),
        'discountItems' => array( // an optional list of discounts
            array(
                'description' => 'Loyalty Discount',
                'netAmount' => '0.5'
            )
        ),
        'shippingCostItems' => array( // any shipping costs?
            array(
                'description' => 'Shipping and Handling',
                'netAmount' => '3.99',
                'taxable' => false // sometimes shipping costs are taxable
            )
        ),
        'taxItems' => array( // any taxes?
            array(
                'name' => 'CA Sales Tax',
                'percent' => '0.0825' // 8.25% CA sales tax
            )
        )
    ),
    'settlementCurrency' => 'EUR' // specifies in which currency you want to settle
));

echo "Status Code: " . $response->httpStatusCode . "\n";
echo "Response Body: " . $response->responseBody . "\n";

if ($response->httpStatusCode != 200) {
    // something went wrong, let's abort and debug by looking at our log file specified above in the client.
    echo "Could not create checkout, please check the logs.";
    exit;
}

// the customer was created
$data = json_decode($response->responseBody, true);
// $data now contains an object as specified in the success response here: https://www.coinqvest.com/en/api-docs#post-customer
$checkoutId = $data['checkoutId']; // store this persistently in your database
$url = $data['url']; // redirect your customer to this URL to complete the payment




