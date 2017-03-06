Simple php class with redsys soap and redirect
================================

[![Packagist](https://img.shields.io/packagist/v/buuum/redsys.svg)](https://packagist.org/packages/buuum/redsys)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](#license)

## Install

### System Requirements

You need PHP >= 5.5.0 to use Buuum\Redsys but the latest stable version of PHP is recommended.

### Composer

Buuum\redsys is available on Packagist and can be installed using Composer:

```
composer require buuum/redsys
```

### Manually

You may use your own autoloader as long as it follows PSR-0 or PSR-4 standards. Just put src directory contents in your vendor directory.


### Documentation

https://canales.redsys.es/canales/ayuda/documentacion/Manual%20integracion%20para%20conexion%20por%20Redireccion.pdf
https://canales.redsys.es/canales/ayuda/documentacion/Manual%20integracion%20para%20conexion%20por%20Web%20Service.pdf


### Payment with redirection
#### OPTION 1.- payment.php (number visa in bank web)
```php
$redsys = new \Buuum\Redsys($redsys_key);

try {
    $redsys->setMerchantcode($redsys_merchant_code);
    $redsys->setAmount($amount);
    $redsys->setOrder($order);
    $redsys->setTerminal($redsys_merchant_terminal);
    $redsys->setCurrency(978);

    $redsys->setTransactiontype('0');
    $redsys->setMethod('C');

    $redsys->setNotification('http://localhost/notification.php'); //Url de notificacion
    $redsys->setUrlOk('http://localhost/payment_ok.php');
    $redsys->setUrlKo('http://localhost/payment_ko.php');

    $redsys->setTradeName('Store S.L');
    $redsys->setTitular('John Doe');
    $redsys->setProductDescription('Product description');

    $form = $redsys->createForm();

} catch (Exception $e) {
    echo $e->getMessage();
    die;
}

echo $form;

```

#### OPTION 2.- payment.php (number visa in web)
```php
$redsys = new \Buuum\Redsys($redsys_key);

try {
    $redsys->setMerchantcode($redsys_merchant_code);
    $redsys->setAmount($amount);
    $redsys->setOrder($order);
    $redsys->setTerminal($redsys_merchant_terminal);
    $redsys->setCurrency(978);

    $redsys->setPan($visa_number);
    $redsys->setExpiryDate($visa_expiry);
    $redsys->setCVV($visa_cvv);
    $redsys->setMerchantDirectPayment(true);

    $redsys->setTransactiontype('0');
    $redsys->setMethod('C');

    $redsys->setNotification('http://localhost/notification.php'); //Url de notificacion
    $redsys->setUrlOk('http://localhost/payment_ok.php');
    $redsys->setUrlKo('http://localhost/payment_ko.php');

    $redsys->setTradeName('Store S.L');
    $redsys->setTitular('John Doe');
    $redsys->setProductDescription('Product description');

    $form = $redsys->createForm();

} catch (Exception $e) {
    echo $e->getMessage();
    die;
}

echo $form;

```

#### notification.php
```php
$redsys = new \Buuum\Redsys($redsys_key);

try{
    $result = $redsys->checkPaymentResponse($_POST);
catch (Exception $e) {
    echo $e->getMessage();
    die;
}

var_dump($result);
```
#### notification result output
##### with error
```php
array (
  'error' => true,
  'code' => 'SIS041',
  'Ds_Date' => '05/03/2017',
  'Ds_Hour' => '08:40',
  'Ds_SecurePayment' => '1',
  'Ds_Card_Country' => '724',
  'Ds_Amount' => '1000',
  'Ds_Currency' => '978',
  'Ds_Order' => '99699629',
  'Ds_MerchantCode' => 'xxxxxx',
  'Ds_Terminal' => '001',
  'Ds_Response' => '0000',
  'Ds_MerchantData' => '',
  'Ds_TransactionType' => '0',
  'Ds_ConsumerLanguage' => '1',
  'Ds_AuthorisationCode' => '875284',
)
```
##### without error
```php
array (
  'error' => false,
  'code' => 0,
  'Ds_Date' => '05/03/2017',
  'Ds_Hour' => '08:40',
  'Ds_SecurePayment' => '1',
  'Ds_Card_Country' => '724',
  'Ds_Amount' => '1000',
  'Ds_Currency' => '978',
  'Ds_Order' => '99699629',
  'Ds_MerchantCode' => 'xxxxxxx',
  'Ds_Terminal' => '001',
  'Ds_Response' => '0000',
  'Ds_MerchantData' => '',
  'Ds_TransactionType' => '0',
  'Ds_ConsumerLanguage' => '1',
  'Ds_AuthorisationCode' => '875284',
)
```

### Payment with web service

#### Payment

```php
$redsys = new \Buuum\Redsys($redsys_key);

try {
    $redsys->setMerchantcode($redsys_merchant_code);
    $redsys->setAmount($amount);
    $redsys->setOrder($order);
    $redsys->setTerminal($redsys_merchant_terminal);
    $redsys->setCurrency(978);
    $redsys->setPan($visa_number);
    $redsys->setExpiryDate($visa_expiry);
    $redsys->setCVV($visa_cvv);
    $redsys->setTransactiontype('A');
    $redsys->setIdentifier('REQUIRED');
    $result = $redsys->firePayment();

} catch (Exception $e) {
    echo $e->getMessage();
    die;
}

var_dump($result);

```

#### Payment with identifier
```php

$redsys = new \Buuum\Redsys($redsys_key);

try {
    $redsys->setMerchantcode($redsys_merchant_code);
    $redsys->setAmount($amount);
    $redsys->setOrder($order);
    $redsys->setTerminal($redsys_merchant_terminal);
    $redsys->setCurrency(978);
    $redsys->setTransactiontype('A');
    $redsys->setIdentifier($client_identifier);
    $result = $redsys->firePayment();
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}
```

#### Pay back from order
```php

$redsys = new \Buuum\Redsys($redsys_key);

try {
    $redsys->setMerchantcode($redsys_merchant_code);
    $redsys->setAmount($amount);
    $redsys->setOrder($order);
    $redsys->setTerminal($redsys_merchant_terminal);
    $redsys->setCurrency(978);
    $redsys->setTransactiontype(3);
    $result = $redsys->firePayment();
    
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}
```

#### result output
##### with error
```php
array (
  'error' => true,
  'code' => 'SIS0051',
  'DS_MERCHANT_MERCHANTCODE' => 'xxxxx',
  'DS_MERCHANT_AMOUNT' => '1000',
  'DS_MERCHANT_ORDER' => '9932453',
  'DS_MERCHANT_TERMINAL' => '001',
  'DS_MERCHANT_CURRENCY' => '978',
  'DS_MERCHANT_PAN' => '4548812049400004',
  'DS_MERCHANT_EXPIRYDATE' => '2012',
  'DS_MERCHANT_CVV2' => '123',
  'DS_MERCHANT_TRANSACTIONTYPE' => 'A',
  'DS_MERCHANT_IDENTIFIER' => 'REQUIRED',
)
```
##### without error
```php
array (
  'error' => false,
  'code' => '0',
  'Ds_Amount' => '1000',
  'Ds_Currency' => '978',
  'Ds_Order' => '1234524534',
  'Ds_Signature' => 'Xfh84TG95t7XRKQV/UGyhH+lXd6PFuGPeU25fgNpGUc=',
  'Ds_MerchantCode' => 'xxxxxx',
  'Ds_Terminal' => '1',
  'Ds_Response' => '0000',
  'Ds_AuthorisationCode' => '415446',
  'Ds_TransactionType' => 'A',
  'Ds_SecurePayment' => '0',
  'Ds_Language' => '1',
  'Ds_ExpiryDate' => '2012',
  'Ds_Merchant_Identifier' => '4597a931b735a7d8e55252e25894fa6dd3a9bed4',
  'Ds_MerchantData' => array (),
  'Ds_Card_Country' => '724',
)
```

### Get errors messages
For get error messages use class https://github.com/eusonlito/redsys-Messages
```php
$error = \Redsys\Messages\Messages::getByCode($result['code']);

# error output
array (
  'code' => 'SIS0051',
  'message' => 'Ds_Merchant_Order Número de pedido repetido',
  'msg' => 'MSG0001',
  'detail' => '',
)
```

## LICENSE

The MIT License (MIT)

Copyright (c) 2016

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.