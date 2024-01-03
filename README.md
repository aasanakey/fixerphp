# Fixer

[![Latest Stable Version](http://poser.pugx.org/aasanakey/fixerphp/v)](https://packagist.org/packages/aasanakey/fixerphp) [![Total Downloads](http://poser.pugx.org/aasanakey/fixerphp/downloads)](https://packagist.org/packages/aasanakey/fixerphp) [![Latest Unstable Version](http://poser.pugx.org/aasanakey/fixerphp/v/unstable)](https://packagist.org/packages/aasanakey/fixerphp) [![License](http://poser.pugx.org/aasanakey/fixerphp/license)](https://packagist.org/packages/aasanakey/fixerphp) [![PHP Version Require](http://poser.pugx.org/aasanakey/fixerphp/require/php)](https://packagist.org/packages/aasanakey/fixerphp)

Fixer is a simple PHP library for current and historical currency exchange rates based on the Apilayer's Fixer API [APILayer (Fixer Api)](https://apilayer.com/marketplace/fixer-api "APILayer marketplace")

## Requirements

- PHP >= 8.0
- guzzlehttp >= 7.8

## Installation

```
composer require aasanakey/fixerphp
```

## Usage

**Package requires an api key**

### 1. Currency Conversion

To convert from one currency to another you may chain the methods:

```php
require 'vendor/autoload.php';

use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->convert()
        ->from('USD')
        ->to('EUR')
        ->get();
```

This will return the converted amount or `null` on failure.

The amount to be converted is default to `1`, you may specify the amount:

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->convert()
        ->from('USD')
        ->to('EUR')
        ->amount(50)
        ->get();
```

#### Available Methods

- Convert currency using historical exchange rates `YYYY-MM-DD`:

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->convert()
        ->from('USD')
        ->to('EUR')
        ->date('2019-08-01')
        ->get();
```

- Round the converted amount to decimal places:

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->convert()
        ->from('USD')
        ->to('EUR')
        ->round(2)
        ->get();
```

### 2. Latest Rates

To get latest rates you may chain the methods:

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->rates()
        ->latest()
        ->get();

 /*
 [
  "base": "USD",
  "date": "2022-04-14",
  "rates": [
    "EUR": 0.813399,
    "GBP": 0.72007,
    "JPY": 107.346001
  ],
  "success": true,
  "timestamp": 1519296206
]
*/
```

This will return an `array` of all available currencies or `null` on failure.

#### Available Methods

- Just like currency conversion you may chain any of the available methods:

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->rates()
        ->latest()
        ->symbols(['USD', 'EUR', 'EGP']) //An array of currency codes to limit output currencies
        ->base('GBP') //Changing base currency (default: EUR). Enter the three-letter currency code of your preferred base currency.
        ->get();
```

### 3. Historical Rates

Historical rates are available for most currencies all the way back to the year of 1999.

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->rates()
        ->historical('2013-12-24') //`YYYY-MM-DD` Required date parameter to get the rates for
        ->get();

/** 
 [
  "base": "GBP",
  "date": "2013-12-24",
  "historical": true,
  "rates": {
    "CAD": 1.739516,
    "EUR": 1.196476,
    "USD": 1.636492
  },
  "success": true,
  "timestamp": 1387929599
 ]
 */

Fixer::key('your api key')
        ->rates()
        ->historical('2013-12-24')
        ->symbols(['USD', 'EUR', 'EGP']) //An array of currency codes to limit output currencies
        ->base('GBP')
        ->get();

/**
[
  "base": "GBP",
  "date": "2013-12-24",
  "historical": true,
  "rates": {
    "CAD": 1.739516,
    "EUR": 1.196476,
    "USD": 1.636492
  },
  "success": true,
  "timestamp": 1387929599
]
*/
```

Same as latest rates you may chain any of the available methods:

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->rates()
        ->historical('2020-01-01')
        ->symbols(['USD', 'EUR', 'CZK'])
        ->base('GBP')
        ->get();
```

### 4. Timeseries Rates

Timeseries are for daily historical rates between two dates of your choice, with a maximum time frame of 365 days.
This will return an `array` or `null` on failure.

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->rates()
        ->timeSeries('2021-05-01', '2021-05-02') //`YYYY-MM-DD` Required dates range parameters
        ->get();

/**
[
  "base": "EUR",
  "end_date": "2012-05-03",
  "rates": {
    "2012-05-01": {
      "AUD": 1.278047,
      "CAD": 1.302303,
      "USD": 1.322891
    },
    "2012-05-02": {
      "AUD": 1.274202,
      "CAD": 1.299083,
      "USD": 1.315066
    },
    "2012-05-03": {
      "AUD": 1.280135,
      "CAD": 1.296868,
      "USD": 1.314491
    }
  },
  "start_date": "2012-05-01",
  "success": true,
  "timeseries": true
]
 */
```

### 5. Fluctuations

Retrieve information about how currencies fluctuate on a day-to-day basis, with a maximum time frame of 365 days.
This will return an `array` or `null` on failure.

```php
use Aasanakey\Fixer\Fixer;

Fixer::key('your api key')
        ->rates()
        ->fluctuations('2021-03-29', '2021-04-15') //`YYYY-MM-DD` Required dates range parameters
        ->symbols(['USD','JPY']) //[optional] An array of currency codes to limit output currencies
        ->base('EUR') //[optional] Changing base currency (default: EUR). Enter the three-letter currency code of your preferred base currency.
        ->get();

/**
 [
  "base": "EUR",
  "end_date": "2018-02-26",
  "fluctuation": true,
  "rates": {
    "JPY": {
      "change": 0.0635,
      "change_pct": 0.0483,
      "end_rate": 131.651142,
      "start_rate": 131.587611
    },
    "USD": {
      "change": 0.0038,
      "change_pct": 0.3078,
      "end_rate": 1.232735,
      "start_rate": 1.228952
    }
  },
  "start_date": "2018-02-25",
  "success": true
 ]
 */
```

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
