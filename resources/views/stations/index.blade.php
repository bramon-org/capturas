@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Estações') }}</div>

                <div class="card-body">
                    <div class="row">
                        @foreach($estacoes['data'] as $station)
                        <div class="col-sm-6 col-lg-3">
                            <div class="card">
                                <img src="https://picsum.photos/320/240?grayscale&random={{ random_int(0, PHP_INT_MAX) }}" alt="Station picture" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $station['name'] }}</h5>
                                    <p class="card-text">
                                        <span class="badge rounded-pill bg-secondary">{{ $station['source'] }}</span> <br>
                                        {{ $station['city'] }} - {{ $station['state'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-center">
                            <li class="page-item @if ($estacoes['current_page'] === 1) disabled @endif">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            @for ($i=1; $i < $estacoes['last_page']; $i++)
                                @if ($estacoes['current_page'] === $i)
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="#">{{ $i }} <span class="sr-only">(current)</span></a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link" href="#">{{ $i }}</a></li>
                                @endif
                            @endfor
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
