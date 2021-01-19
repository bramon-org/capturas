@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Estação') }}</div>

                <div class="card-body">
                    <div class="row">
                        <form class="form" method="post" action="">
                            <input type="hidden" name="id" value="{{ $estacao['station']['id'] }}">
                            <input type="hidden" name="user_id" value="{{ $estacao['station']['user_id'] }}">

                            <label for="station-source" class="form-label p-2">
                                <span>Fonte</span>
                                <select name="source" id="station-source" class="form-control">
                                    <option value="UFO" selected="selected">UFO Capture</option>
                                    <option value="RMS">RMS</option>
                                </select>
                            </label>
                            <label for="station-name" class="form-label p-2">
                                <span>Nome</span>
                                <input type="text" class="form-control" name="name" id="station-name" value="{{ $estacao['station']['name'] }}" required>
                            </label>
                            <label for="station-city" class="form-label p-2">
                                <span>Cidade</span>
                                <input type="text" class="form-control" name="city" id="station-city" value="{{ $estacao['station']['city'] }}" required>
                            </label>
                            <label for="station-state" class="form-label p-2">
                                <span>Estado</span>
                                <input type="text" class="form-control" name="state" id="station-state" value="{{ $estacao['station']['state'] }}" required>
                            </label>
                            <label for="station-country" class="form-label p-2">
                                <span>País</span>
                                <input type="text" class="form-control" name="country" id="station-country" value="{{ $estacao['station']['country'] }}" required>
                            </label>
                            <label for="station-latitude" class="form-label p-2">
                                <span>Latitude</span>
                                <input type="number" class="form-control" name="latitude" id="station-latitude" value="{{ $estacao['station']['latitude'] }}" required>
                            </label>
                            <label for="station-longitude" class="form-label p-2">
                                <span>Longitude</span>
                                <input type="number" class="form-control" name="longitude" id="station-longitude" value="{{ $estacao['station']['longitude'] }}" required>
                            </label>
                            <label for="station-azimuth" class="form-label p-2">
                                <span>Azimute</span>
                                <input type="number" class="form-control" name="azimuth" id="station-azimuth" value="{{ $estacao['station']['azimuth'] }}" required>
                            </label>
                            <label for="capture_date" class="form-label p-2">
                                <span>Elevação</span>
                                <input type="number" class="form-control" name="elevation" id="station-elevation" value="{{ $estacao['station']['elevation'] }}" required>
                            </label>
                            <label for="station-fov" class="form-label p-2">
                                <span>FOV</span>
                                <input type="number" class="form-control" name="fov" id="station-fov" value="{{ $estacao['station']['fov'] }}" required>
                            </label>
                            <label for="station-camera-model" class="form-label p-2">
                                <span>Câmera</span>
                                <input type="text" class="form-control" name="camera_model" id="station-camera-model" value="{{ $estacao['station']['camera_model'] }}" required>
                            </label>
                            <label for="station-camera-lens" class="form-label p-2">
                                <span>Lente</span>
                                <input type="text" class="form-control" name="camera_lens" id="station-camera-lens" value="{{ $estacao['station']['camera_lens'] }}" required>
                            </label>
                            <label for="station-camera-capture" class="form-label p-2">
                                <span>Dispositivo de Captura</span>
                                <input type="text" class="form-control" name="camera_capture" id="station-camera-capture" value="{{ $estacao['station']['camera_capture'] }}" required>
                            </label>
                            <label for="station-active" class="form-label p-2">
                                <span>Ativa</span>
                                <input type="checkbox" class="form-control" name="active" id="station-active" value="1" @if ($estacao['station']['active']) checked="checked" @endif>
                            </label>
                            <label for="station-visible" class="form-label p-2">
                                <span>Visível no site</span>
                                <input type="checkbox" class="form-control" name="visible" id="station-visible" value="1" @if ($estacao['station']['visible']) checked="checked" @endif>
                            </label>

                            <br>

                            <input type="submit" class="btn btn-primary" value="Salvar">
                        </form>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <a href="{{ route('stations.index', []) }}">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
