@extends('layout.layout')
@section('upper')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a href="{{route('index')}}" class="navbar-brand" style="text-decoration: none; ">OPENSEAPI</a>
        <div class="nav-item">
            <form action="{{route('getNfts')}}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text">Wallet number</span>
                    <input type="text" name="wallet" class="form-control">
                    <button type="submit" class="btn btn-primary">Get</button>
                </div>
            </form>
        </div>
    </nav>
@endsection
@section('content')
    @if(isset($nfts))
        <div class="row row-cols-6">
            @foreach($nfts as $nft)
                <div class="col">
                    <div class="card" style="width: 18rem;">
                        <a href="{{"https://opensea.io/".$nft['a']}}">
                            <img src="{{$nft['img']}}" class="card-img-top" alt="{{$nft['name']}}">
                            <div class="card-body">
                                <h5 class="card-title">{{$nft['name']}}</h5>
                                <p class="card-text">{{$nft['creator']}}</p>
                            </div>
                        </a>
                    </div>
                </div>
          @endforeach
        </div>
    @endif
@endsection
