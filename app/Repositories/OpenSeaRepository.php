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
        $data = @file_get_contents("https://opensea.io/".$owner,false   ,$this->context);

        if (!in_array("HTTP/1.1 200 OK",$http_response_header)) {
            return false;
        }

        $html = str_get_html($data);


        $divs = $html->find('div[role] article');


        $nfts = $this->parsingAndPreparing($divs);

        $html->clear();
        return $nfts;

    }
}
