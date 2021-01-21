@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Operadores') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            @forelse($operadores['data'] as $operador)
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card">
                                        <img src="https://picsum.photos/id/1/320/240?grayscale" alt="Station picture" class="card-img-top">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $operador['name'] }}</h5>
                                            <p class="card-text">
                                                {{ $operador['email'] }} <br>

                                                @if (env('BRAMON_API_ROLE') === 'admin')
                                                    <a href="{{ route('operators.edit', ['id' => $operador['id']]) }}" class="btn btn-link">Editar</a>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert">
                                    Sem operadores
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-center">
                            <li class="page-item @if ($operadores['current_page'] === 1) disabled @endif">
                                <a class="page-link" href="?page={{ $operadores['current_page'] - 1 }}" tabindex="-1" aria-disabled="true">Anterior</a>
                            </li>
                            @for ($i=1; $i < $operadores['last_page']; $i++)
                                @if ($operadores['current_page'] === $i)
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }} <span class="sr-only">(atual)</span></a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                                @endif
                            @endfor
                            <li class="page-item">
                                <a class="page-link @if (empty($capturas['next_page_url'])) disabled @endif" href="?page={{ $operadores['current_page']  + 1 }}">Próxima</a>
                            </li>
                        </ul>
                    </nav>

                    @if (env('BRAMON_API_ROLE') === 'admin')
                        <a href="{{ route('operators.new') }}">Adicionar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
