@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Capturas em tempo real') }}</div>

                <div class="card-body">
                    <div class="row">
                        <form method="get" action="" class="form">
                            <ul class="list-inline p-2">
                                @foreach($estacoes['data'] as $station)
                                <li class="list-inline-item">
                                    <label for="station_{{ $station['id'] }}">
                                        <input type="checkbox" id="station_{{ $station['id'] }}" name="station[]" value="{{ $station['id'] }}" @if (in_array($station['id'], request()->get('station', []))) checked="checked" @endif>
                                        {{ $station['name'] }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>

                            <div class="d-inline-flex">
                                <label for="capture_date" class="form-label p-2">
                                    <span>Data da captura:</span>
                                    <input type="date" class="form-control" name="date" id="capture_date" value="{{ request()->get('date') }}">
                                </label>

                                <label for="capture_radiant" class="form-label p-2">
                                    <span>Radiante:</span>
                                    <select name="radiant" id="capture_radiant" class="form-control">
                                        <option value=""></option>
                                        @foreach ($radiantes as $radiant_id => $radiant_name)
                                            <option value="{{ $radiant_id }}" @if (in_array($radiant_id['id'], request()->get('radiant'))) selected="selected" @endif>{{ $radiant_id }} - {{ $radiant_name }}</option>
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
                        @foreach ( $capturas['data'] as $capture)
                            @php
                                $imagem = array_filter($capture['files'], function($file) {
                                    return substr_count($file['filename'], 'T.jpg') !== 0;
                                });
                                $imagem = array_pop($imagem);
                            @endphp

                            <div class="col-sm-6 col-lg-3">
                                <div class="card">
                                    <a href="{{ str_replace('T.jpg', 'P.jpg', $imagem['url']) }}" data-lightbox="roadtrip">
                                        <img src="{{ $imagem['url'] }}" alt="{{ $imagem['filename'] }}" class="card-img-top">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $capture['station']['name'] }}</h5>
                                        <p class="card-text">
                                            @if ($capture['class'] !== '')
                                                <span class="badge rounded-pill bg-secondary">Não analisado</span> <br>
                                            @else
                                                <span class="badge rounded-pill bg-primary">{{ $capture['class'] }}</span> <br>
                                            @endif
                                            {{ (new DateTime($capture['captured_at']))->format('d/m/Y H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-muted">
                    @php
                    $parameters = request()->all();
                    @endphp
                    <nav aria-label="...">
                        <ul class="pagination justify-content-center">
                            @php
                                $prevParams = $parameters;
                                $prevParams['page'] = $capturas['current_page'] - 1;
                            @endphp
                            <li class="page-item @if ($capturas['current_page'] === 1) disabled @endif">
                                <a class="page-link" href="?{{ urldecode(http_build_query($prevParams)) }}" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            {{--
                            @for ($i=1; $i < $capturas['last_page']; $i++)
                                @if ($capturas['current_page'] === $i)
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }} <span class="sr-only">(current)</span></a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                                @endif
                            @endfor
                            --}}
                            <li class="page-item">
                                @php
                                    $nextParams = $parameters;
                                    $nextParams['page'] = $capturas['current_page'] + 1;
                                @endphp

                                <a class="page-link" href="?{{ urldecode(http_build_query($nextParams)) }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
