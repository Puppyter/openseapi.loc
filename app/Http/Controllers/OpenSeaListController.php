<?php

namespace App\Http\Controllers;

use App\Repositories\OpenSeaRepository;
use Illuminate\Http\Request;

class OpenSeaListController extends Controller
{
    public function display(Request $request, OpenSeaRepository $openSeaRepository)
    {
        $nfts = $openSeaRepository->get($request->wallet);
        if ($nfts == false){
            return response()->view('list',['message'=>"Bad Request"]);
        }
        return response()->view('list',['nfts' => $nfts]);
    }

    public function index()
    {
        return response()->view('list');
    }
}
