@extends('layouts.admin')

@section('content')
<section class="bdy content reset cflex">
    <div class="c100 blk __content-mount">
        <div class="container-fluid admin-container">
            <div class="row d-flex justify-content-center">
                <div class="col-8 mt-4" id="players">
                    <h3>Игроки</h3>
                    <form action="{{ route('admin.players.save') }}" @submit.prevent="savePlayers">
                        {{ csrf_field() }}
                        <div v-for="player in newPlayers" class="bordered-form">
                            <div class="form-group">
                                <input class="form-control" placeholder="Введите ник" :name="'players[' + player + '][name]'" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Введите ссылку на профиль" :name="'players[' + player + '][profile]'" value="">
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <a class="new-input-icon" @click="newPlayers += 1">
                                <span class="icon-medium icons-add-new-medium"></span>
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer-scripts')
    <script>
        new Vue({
            el: '#players',
            data: {
                newPlayers: 1
            },
            methods: {
                savePlayers: function(e) {
                    var form = e.target;
                    var data = new FormData(form);
                    this.$http
                        .post(form.action, data)
                        .then(response => {
                            if(response.data) {
                                this.successIziToast('Игрок(и) успешно сохранены');
                            } else {
                                this.whoopsIziToast('Ошибка при сохранении игрока(ов)');
                            }
                        }).catch((err) => {
                            console.log(error);
                            this.whoopsIziToast('Ошибка при сохранении');
                        })


                },
                successIziToast: function(message) {
                    iziToast.show({
                        title: '',
                        color: 'green',
                        message: message,
                        position: 'topRight',
                        timeout: 3000,
                        onClosed: function () {
                            window.location.reload(true);
                        }
                    });
                },      
                whoopsIziToast: function(message){
                    var params = {
                        title: 'Whoops: Что-то пошло не так',
                        color: 'red',
                        message: message,
                        position: 'topRight',
                        timeout: 5000
                    };
                    iziToast.show(params);
                }
            }
        })
    </script>
@endsection