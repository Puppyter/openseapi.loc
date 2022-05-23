<?php

namespace App\Http\Controllers;

use App\Repositories\OpenSeaRepository;
use Illuminate\Http\Request;

class OpenSeaListController extends Controller
{
    public function display(Request $request, OpenSeaRepository $openSeaRepository)
    {
        $items = $openSeaRepository->get("0x227c7DF69D3ed1ae7574A1a7685fDEd90292EB48");
        return dd($items);
    }
}
