<?php

namespace YahooStockPrices;

class PriceFetcher {
    /**
     * Download and save the historical prices for some companies.
     *
     * @param  string
     * @param  array
     * @return mixed
     *
     * @todo Clean this shit.
     */
    public static function getPrices($path, $companies) {
        // $companies = ['CBA.AX'];
        // $companies = $this->argument('companies');
        $response = self::downloadURL('https://au.finance.yahoo.com/quote/RBL.AX/history');
        $response = str_replace('root.App.main = ', '', $response);
        $lines = explode("\n", $response);
        $cookies = preg_grep("/Set-Cookie/", $lines);
        preg_match("/B=(.*?);/", array_shift($cookies), $cookie);
        $cookie = $cookie[1];
        $crumbs = preg_grep("/CrumbStore/", $lines);
        $crumbs = rtrim(array_shift($crumbs), ';');
        $json = json_decode($crumbs, true);
        $crumb = $json['context']['dispatcher']['stores']['CrumbStore']['crumb'];

        print_r("Using cookie : ".$cookie."\n");
        print_r("Using crumb  : ".$crumb."\n");

        $headers = [
            'Cookie: B='.$cookie,
            'User-Agent: Mozilla/5.0 (X11; U; Linux i686) Gecko/20071127 Firefox/2.0.0.11',
        ];
        foreach ($companies as $company) {
            $response = self::downloadURL('https://query1.finance.yahoo.com/v7/finance/download/'
                .$company
                .'?period1=0'
                .'&period2='.time()
                .'&interval=1d'
                .'&events=history'
                .'&crumb='.$crumb,
                $headers, 0);
            $filename = $path.$company.'.csv';
            // Storage::put($filename, $response);
            // $filesize = Storage::size($filename);
            // print_r(str_pad('Fetched '.$company, 13).': '.round($filesize / 1024)."K\n");
            file_put_contents($filename, $response);
            print_r("Written ".$company." to ".$filename."\n");
        }
    }

    /**
     * Helper function to download from URL.
     *
     * Function uses cURL extension so you need to install it.
     *
     *     `sudo apt install php7.0-curl`
     *
     * http://www.jonasjohn.de/snippets/php/curl-example.htm
     *
     * @param  string
     * @param  array
     * @param  int
     * @return string
     */
    public static function downloadURL($url, $headers_request = null, $headers_response = 1) {
        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die("Sorry cURL is not installed!\n");
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // Now set some options (most are optional)

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $url);

        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686) "
            ."Gecko/20071127 Firefox/2.0.0.11");

        // Headers
        if (isset($headers_request)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_request);
        }

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, $headers_response);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        // Close the cURL resource, and free system resources
        curl_close($ch);
        return $output;
    }
}
