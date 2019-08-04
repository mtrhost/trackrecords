@extends('layouts.admin')

@section('content')
<section class="bdy content reset cflex">
    <div class="c100 blk __content-mount">
        <div class="container-fluid">
            <!-- User Interface controls -->
            <div class="row page__header-row">
                <div class="col-4 mx-auto">
                    <div class="card">
                        <div class="card-header">{{ __('Авторизация') }}</div>
        
                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
        
                                <div class="form-group row form-inline">
                                    <label for="name" class="col-sm-4 col-form-label d-inline-block">{{ __('Логин') }}</label>
        
                                    <div class="col-md-6 d-inline">
                                        <input id="name" type="string" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
        
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row form-inline">
                                    <label for="password" class="col-md-4 col-form-label d-inline-block">{{ __('Пароль') }}</label>
        
                                    <div class="col-md-6 d-inline">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
        
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                @if ($errors->has('role'))
                                    <div class="form-group row offset-md-3">
                                        <span class="alert alert-danger" role="alert">
                                            <strong>{{ $errors->first('role') }}</strong>
                                        </span>
                                    </div>
                                @endif
        
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Логин') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
