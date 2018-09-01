<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use YahooStockPrices\PriceFetcher;

PriceFetcher::getPrices('/home/khanh/Projects/', ['CBA.AX']);
