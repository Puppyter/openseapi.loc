<?php

namespace App\Repositories;

include 'simple_html_dom.php';


class OpenSeaRepository
{

    private $context;

    public function __construct()
    {
        $this->context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );
    }

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
        $data = file_get_contents("https://opensea.io/".$owner,true,$this->context);
        $html = str_get_html($data);

        $divs = $html->find('div[class=sc-1xf18x6-0 bSaLsG]');

        $nfts = $this->parsingAndPreparing($divs);

        $html->clear();
        return $nfts;

    }
}
