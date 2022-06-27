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

        for ($i=0; $i<array_key_last($imgs); $i++){
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
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,"https://opensea.io/".$owner);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $query = curl_exec($curl_handle);

        $html = str_get_html($query);

        $divs = $html->find('div[class=sc-1xf18x6-0 bSaLsG]');

        $nfts = $this->parsingAndPreparing($divs);

        $html->clear();
        curl_close($curl_handle);
        return $nfts;

    }
}
