<?php

namespace App\Repositories;

include 'simple_html_dom.php';


class OpenSeaRepository
{

    private function parsingAndPreparing(array $divs) :array
    {
        foreach ($divs as $div) {
            foreach ($div->find("img [src]")as $img) {
                $imgs[] = $img->attr['src'];
            }
            foreach ($div->find("a[class=sc-1pie21o-0 elyzfO Asset--anchor]") as $a){
                $as[] = $a->attr['href'];
            }
            foreach ($div->find('div[class=sc-7qr9y8-0 sc-nedjig-1 iUvoJs fyXutN]') as $name) {
                $names[] = $name->plaintext;
            }
            foreach ($div->find('div[class=sc-7qr9y8-0 sc-nedjig-1 iUvoJs eewaH]')as $creator) {
                $creators[] = $creator->plaintext;
            }
        }

        for ($i=0; $i<array_key_last($as); $i++){
            $nfts[$i] =[
                'img'=>$imgs[$i],
                'a' => $as[$i],
                'name' => $names[$i],
                'creator' => $creators[$i]

            ];
        }

        return $nfts;
    }

    public function get(string $owner) :array
    {
        $curlHandle=curl_init();
        $cookie = tempnam ("/tmp", "CURLCOOKIE");
        curl_setopt($curlHandle, CURLOPT_URL,"https://opensea.io/".$owner);
        curl_setopt( $curlHandle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt( $curlHandle, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $curlHandle, CURLOPT_ENCODING, "" );
        curl_setopt( $curlHandle, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curlHandle, CURLOPT_AUTOREFERER, true );
        curl_setopt( $curlHandle, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
        curl_setopt( $curlHandle, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        $query = curl_exec($curlHandle);
        dd($query);
        $html = str_get_html($query);

        $divs = $html->find('div[class=sc-1xf18x6-0 bSaLsG]');

        $nfts = $this->parsingAndPreparing($divs);

        $html->clear();
        curl_close($curlHandle);
        return $nfts;

    }
}
