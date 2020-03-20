# VAT Validator

<a href="https://packagist.org/packages/florowebdevelopment/vat-validator"><img src="https://poser.pugx.org/florowebdevelopment/vat-validator/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/florowebdevelopment/vat-validator"><img src="https://poser.pugx.org/florowebdevelopment/vat-validator/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/florowebdevelopment/vat-validator"><img src="https://poser.pugx.org/florowebdevelopment/vat-validator/license.svg" alt="License"></a>
<img src="https://github.styleci.io/repos/177171886/shield?style=flat" alt="StyleCI">

This library validate VAT numbers by using regular expressions and the VIES (VAT Information Exchange System) service.

## Install

```
composer require florowebdevelopment/vat-validator
```

## Usage

```php
use Florowebdevelopment\VatValidator\VatValidator;
```

```
$oVatValidator = new VatValidator;

$oVatValidator->validate('NL821783981B01'); // true

if ($oVatValidator->isValid()) {
    $aMetaData = $oVatValidator->getMetaData();
    
    /*
    array(
        "name" => "FLORO WEBDEVELOPMENT B.V.",
        "address" => "WESTBLAAK 00180 3012KN ROTTERDAM"
    )
    */
}
```

## Strict (optional)

When strict is set to FALSE, the VAT number validation returns TRUE if the VIES service is not available (CURL timeout or SoapFault).

```
$oVatValidator->setStrict(false); // default = true
```

## Comments

We suggest that you cache the valid VAT numbers in your application to prevent multiple requests to the VIES service.
