@extends('templates.app')
@section('title','Сеттинги')
@section('layout')
<section class="container-fluid settings" id="settings-list">
    <div class="row">
        <div class="col-lg-3 col-sm-12 hidden-sm-down">
            <div class="data-block">
                <div class="widget widget-search widget-filter">
                    <form id="finder-form" method="GET" action="{{ route('setting.index') }}">
                        <div class="form-group">
                            <label class="text-danger">Название:</label>
                            <input class="form-control" name="name" type="text" value="{{ app('request')->input('name') }}" placeholder="Введите название">
                        </div>
                        <div class="form-group">
                            <label class="text-danger">Кол-во игроков:</label>
                            <input class="form-control" name="players_count" type="text" value="{{ app('request')->input('players_count') }}" placeholder="Введите кол-во игроков">
                        </div>
                        <div class="form-group">
                            <label class="text-danger">Автор:</label>
                            <input class="form-control" name="author" type="text" value="{{ app('request')->input('author') }}" placeholder="Введите имя автора">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success">Поиск</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9 filter-results card custom-card data-block table-responsive">
            <div class="m-y-10">
                <h5 class="available-players">Всего сеттингов: {{ $settings->total() }}</h5>
            </div>

                        
            <div class="post">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Название</th>
                            <th scope="col">Кол-во игроков</th>
                            <th scope="col">Автор</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$settings->isEmpty())
                            @foreach($settings as $setting)
                                <tr>
                                    <th scope="row">{{ $setting->id }}</th>
                                    <td><a href="{{ route('setting.details', $setting->id) }}">{{ $setting->name }}</a></td>
                                    <td>{{ $setting->players_count }}</td>
                                    <td><a href="{{ route('player.details', $setting->author->id) }}">{{ $setting->author->name }}</a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="filter-pagination m-b-30">
                {{ $settings->links() }}
            </div>

        </div>
    </div>
</section>
@endsection