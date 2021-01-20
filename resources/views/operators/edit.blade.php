@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Operador') }}</div>

                <div class="card-body">
                    <div class="row">
                        <form class="form" method="post" action="">
                            <input type="hidden" name="id" value="{{ $operador['station']['id'] }}">

                            <label for="operator-name" class="form-label p-2">
                                <span>Nome</span>
                                <input type="text" class="form-control" name="name" id="operator-name" value="{{ $operador['station']['name'] ?? '' }}" required>
                            </label>
                            <label for="operator-email" class="form-label p-2">
                                <span>Email</span>
                                <input type="email" class="form-control" name="email" id="operator-email" value="{{ $operador['station']['email'] ?? '' }}" required>
                            </label>
                            <label for="operator-phone-number" class="form-label p-2">
                                <span>Telefone</span>
                                <input type="text" class="form-control" name="mobile_phone" id="operator-phone-number" value="{{ $operador['station']['mobile_phone'] ?? '' }}" required>
                            </label>
                            <br>
                            <input type="hidden" name="role" value="operator">
                            <input type="submit" class="btn btn-primary" value="Salvar">
                        </form>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <a href="{{ route('operators.index', []) }}">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
