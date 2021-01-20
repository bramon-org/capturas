@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Análises') }}</div>

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
                                <label for="capture_date_start" class="form-label p-2">
                                    <span>Data inicial:</span>
                                    <input type="date" class="form-control" name="date_start" id="capture_date_start" value="{{ request()->get('date_start') }}" required>
                                </label>
                                <label for="capture_time_start" class="form-label p-2">
                                    <span>Hora inicial:</span>
                                    <input type="time" class="form-control" name="time_start" id="capture_time_start" value="{{ request()->get('time_start', '00:00') }}" required>
                                </label>
                                <label for="capture_date_end" class="form-label p-2">
                                    <span>Data final:</span>
                                    <input type="date" class="form-control" name="date_end" id="capture_date_end" value="{{ request()->get('date_end') }}" required>
                                </label>
                                <label for="capture_time_end" class="form-label p-2">
                                    <span>Hora final:</span>
                                    <input type="time" class="form-control" name="time_end" id="capture_time_end" value="{{ request()->get('time_end', '23:59') }}" required>
                                </label>

                                <label for="capture_radiant" class="form-label p-2">
                                    <span>Radiante:</span>
                                    <select name="radiant" id="capture_radiant" class="form-control">
                                        <option value=""></option>
                                        @foreach ($radiantes as $radiant_id => $radiant_name)
                                            <option value="{{ $radiant_id }}" @if ($radiant_id == request()->get('radiant')) selected="selected" @endif>{{ $radiant_id }} - {{ $radiant_name }}</option>
                                        @endforeach
                                    </select>
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
                        @if (array_key_exists('data', $capturas))
                            @foreach ($capturas['data'] as $captura)
                                @php
                                    $imagem = array_filter($captura['files'], function($file) {
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
                                            <h5 class="card-title">{{ $captura['station']['name'] }}</h5>
                                            <p class="card-text">
                                                @if ($captura['class'] == '')
                                                    <span class="badge rounded-pill bg-secondary">Não analisado</span> <br>
                                                @else
                                                    <span class="badge rounded-pill bg-primary">{{ $captura['class'] }}</span> <br>
                                                @endif

                                                {{ (new DateTime($captura['captured_at']))->format('d/m/Y H:i:s') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div id="map" class="map"></div>
                        <script type="text/javascript">
                            var map = new ol.Map({
                                target: 'map',
                                layers: [
                                    new ol.layer.Tile({
                                        source: new ol.source.OSM()
                                    })
                                ],
                                view: new ol.View({
                                    center: ol.proj.fromLonLat([-48.58, -27.59]),
                                    zoom: 6
                                })
                            });
                        </script>
                    </div>
                </div>
                <div class="card-footer text-muted">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
