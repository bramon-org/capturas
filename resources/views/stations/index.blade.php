@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Estações') }}</div>

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
                        </form>
                    </div>

                    <hr>

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
