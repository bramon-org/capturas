@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Estações') }}</div>

                <div class="card-body">

                    <div class="row">
                        <form method="get" action="" class="form">
                            <div class="d-inline-flex">
                                <label for="station-source" class="form-label p-2">
                                    <span>Fonte</span>
                                    <select name="source" id="station-source" class="form-control">
                                        <option value="UFO"  @if ('UFO' == request()->get('source', 'UFO')) selected="selected" @endif>UFO Capture</option>
                                        <option value="RMS"  @if ('RMS' == request()->get('source', 'UFO')) selected="selected" @endif>RMS</option>
                                    </select>
                                </label>
                                <label for="station-name" class="form-label p-2">
                                    <span>Nome</span>
                                    <input type="text" class="form-control" name="name" id="station-name" value="{{ request()->get('name') }}">
                                </label>
                                <label for="station-city" class="form-label p-2">
                                    <span>Cidade</span>
                                    <input type="text" class="form-control" name="city" id="station-city" value="{{ request()->get('city') }}">
                                </label>
                                <label for="station-state" class="form-label p-2">
                                    <span>Estado</span>
                                    <input type="text" class="form-control" name="state" id="station-state" value="{{ request()->get('state') }}">
                                </label>
                                <label for="station-country" class="form-label p-2">
                                    <span>País</span>
                                    <input type="text" class="form-control" name="country" id="station-country" value="{{ request()->get('country') }}">
                                </label>
                                <label for="station-active" class="form-label p-2">
                                    <span>Ativa</span>
                                    <input type="checkbox" class="form-control" name="active" id="station-active" value="1" @if (request()->get('active')) checked="checked" @endif>
                                </label>
                                <label for="station-visible" class="form-label p-2">
                                    <span>Visível</span>
                                    <input type="checkbox" class="form-control" name="visible" id="station-visible" value="1" @if (request()->get('visible')) checked="checked" @endif>
                                </label>

                                <label for="capture_limit" class="form-label p-2">
                                    <span>Limite:</span>
                                    <select name="limit" id="capture_limit" class="form-control">
                                        @foreach ([12, 24, 48, 100] as $limit)
                                            <option value="{{ $limit }}" @if ($limit == request()->get('limit', 12)) selected="selected" @endif>{{ $limit }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <span class="p-2 align-self-end">
                                    <input type="submit" class="btn btn-primary" value="Buscar" style="margin-bottom: 8px !important">
                                </span>
                            </div>

                        </form>
                    </div>

                    <hr>

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
                    <nav aria-label="...">
                        <ul class="pagination justify-content-center">
                            <li class="page-item @if ($estacoes['current_page'] === 1) disabled @endif">
                                <a class="page-link" href="?page={{ $estacoes['current_page'] - 1 }}" tabindex="-1" aria-disabled="true">Anterior</a>
                            </li>
                            @for ($i=1; $i < $estacoes['last_page']; $i++)
                                @if ($estacoes['current_page'] === $i)
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }} <span class="sr-only">(atual)</span></a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                                @endif
                            @endfor
                            <li class="page-item">
                                <a class="page-link" href="?page={{ $estacoes['current_page']  + 1 }}">Próxima</a>
                            </li>
                        </ul>
                    </nav>

                    @if (env('BRAMON_API_ROLE') === 'admin')
                    <a href="{{ route('stations.new') }}">Adicionar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
