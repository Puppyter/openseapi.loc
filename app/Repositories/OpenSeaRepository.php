<?php

namespace App\Repositories;

include 'simple_html_dom.php';


class OpenSeaRepository
{

    private $context;

    public function __construct()
    {
        $this->context = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    }

    private function parsingAndPreparing(array $divs) :array
    {
        foreach ($divs as $div) {
            foreach ($div->find('img[src^=https]')as $img) {
                $imgs[] = $img->src;
            }
            foreach ($div->find("a[href*=/assets/ethereum]") as $a){
                if ($a->href !=null)
                $as[] = $a->href;

            }
            foreach ($div->find('div[font-weight="600"]') as $name) {
                $names[] = $name->plaintext;
            }
            foreach ($div->find('div[role*=link] div[font-weight="400"]')as $creator) {
                $creators[] = $creator->plaintext;
            }
        }
        $imgs = array_unique($imgs);
        $as = array_unique($as);
        $names = array_unique($names);
        for ($i=0; $i<array_key_last($as); $i++){
            $nfts[$i] =[
                'img'=>$imgs[$i],
                'a' => $as[$i],
                'name' => $names[$i],
                'creator' => $creators[$i]

            ];
        }

        return ($nfts);
    }

    public function get(string $owner)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,"https://opensea.io/".$owner);
        curl_setopt($curl, CURLOPT_AUTOREFERER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->context);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec($curl);
//        if (curl_getinfo($curl,CURLINFO_HTTP_CODE)!= 200)
//        {
//            curl_close($curl);
//            return false;
//        }
        curl_close($curl);
        dd($data);
        $html = str_get_html($data);


        $divs = $html->find('div[role] article');


        $nfts = $this->parsingAndPreparing($divs);

        $html->clear();
        return $nfts;

    }
}
