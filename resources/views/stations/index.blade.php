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
                                    @if (env('BRAMON_API_ROLE') === 'admin')
                                    <a href="{{ route('stations.edit', ['id' => $station['id']]) }}" class="btn btn-link">Editar</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-muted">
                    @if (env('BRAMON_API_ROLE') === 'admin')
                    <a href="{{ route('stations.new') }}">Adicionar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
