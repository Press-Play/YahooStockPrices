# YahooStockPrices

Download and save the historical prices for some companies.

Based off https://github.com/c0redumb/yahoo_quote_download

## Usage

```
<?php

require 'FetchPrices.php';

\YahooStockPrices\PriceFetcher::getPrices('/home/user/Projects/', ['CBA.AX']);
```