@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Capturas em tempo real') }}</div>

                <div class="card-body">
                    <div class="row">
                        <form method="get" action="" class="captures_form">
                            <ul class="station_list">
                                @foreach($estacoes['data'] as $station)
                                <li class="check-estacao">
                                    <label for="station_{{ $station['id'] }}">
                                        <input type="checkbox" id="station_{{ $station['id'] }}" name="station[]" value="{{ $station['id'] }}">
                                        {{ $station['name'] }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>

                            <br style="clear: both">

                            <label for="capture_date" class="filter_bottom">
                                <span>Data da captura:</span>
                                <input type="date" name="capture_date" id="capture_date" value="">
                            </label>

                            <label for="capture_radiant" class="filter_bottom">
                                <span>Radiante:</span>
                                <select name="capture_radiant" id="capture_radiant">
                                    <option value=""></option>
                                    @foreach ($radiantes as $radiant_id => $radiant_name)
                                        <option value="{{ $radiant_id }}">{{ $radiant_id }} - {{ $radiant_name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <input type="submit" value="Buscar">
                        </form>
                    </div>

                    <hr>

                    <div class="row">
                        <ul class="captures_list">
                            @foreach ( $capturas['data'] as $capture)
                                @php
                                    $imagem = array_filter($capture['files'], function($file) {
                                        return substr_count($file['filename'], 'T.jpg') !== 0;
                                    });
                                    $imagem = array_pop($imagem);
                                @endphp

                                <li class="col-sm-6 col-lg-3">
                                    <div class="bramon-captura">
                                        <a href="{{ str_replace('T.jpg', 'P.jpg', $imagem['url']) }}" data-lightbox="roadtrip">
                                            <img src="{{ $imagem['url'] }}" alt="{{ $imagem['filename'] }}">
                                        </a>
                                        <br>
                                        @if ($capture['class'] !== '')
                                            <span class="bramon-analisado">Não analisado</span> <br>
                                        @else
                                            <span class="bramon-analise">{{ $capture['class'] }}</span> <br>
                                        @endif
                                        <span class="bramon-estacao">{{ $capture['station']['name'] }}</span><br>
                                        <span class="bramon-captured">{{ (new DateTime($capture['captured_at']))->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <br style="clear: both">

                    <nav aria-label="...">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
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
