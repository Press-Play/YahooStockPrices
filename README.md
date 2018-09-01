# YahooStockPrices

Download and save the historical prices for companies from Yahoo.

Based off https://github.com/c0redumb/yahoo_quote_download

## Usage

```
<?php

require 'PriceFetcher.php';

\YahooStockPrices\PriceFetcher::getPrices('/home/user/Projects/', ['CBA.AX']);
```