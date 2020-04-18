@extends('templates.app')
@section('title','Сеттинги')
@section('layout')
@section('footer-scripts')
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/datatables.min.js') }}"></script>
    <script>
        new Vue({
            el: '#settings-list',
            data: {
                settings: <?php echo $settings->toJson() ?>
            },
            methods: {
                
            },
            mounted: function() {
                $(".settings-table").DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                    },
                    "bLengthChange": false,
                    "pageLength": 50,
                    "order": [[ 0, "desc" ]]
                    //"ordering": false
                });
            },
            created: function(){
                /*for (var player in this.players) {
                    setTimeout(this.getLastActivity(this.players[player].id), 5000);
                }*/
            }
        });
    </script>
@endsection
<section class="container-fluid settings" id="settings-list">
    <div class="row">

        <div class="col-lg-12 filter-results card custom-card data-block table-responsive">
            <div class="m-y-10">
                <h5 class="available-players">Всего сеттингов: {{ $settings->count() }}</h5>
            </div>
            
            <div class="post">
                <table class="table settings-table">
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

        </div>
    </div>
</section>
@endsection