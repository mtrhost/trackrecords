@extends('layouts.admin')

@section('content')
<section class="bdy content reset cflex">
    <div class="c100 blk __content-mount">
        <div class="container-fluid admin-container">
            <div class="row d-flex justify-content-center">
                <div class="col-8 mt-4" id="roles">
                    <h3>Роли</h3>
                    <form action="{{ route('admin.roles.save') }}" @submit.prevent="saveRoles">
                        {{ csrf_field() }}
                        <div class="form-group form-inline">
                            <input v-for="role in newRoles" class="form-control overwhelm-input" placeholder="Введите название роли" name="roles[]" value="">
                            <a class="new-input-icon" @click="newRoles += 1">
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
            el: '#roles',
            data: {
                newRoles: 1
            },
            methods: {
                saveRoles: function(e) {
                    var form = e.target;
                    var data = new FormData(form);
                    this.$http
                        .post(form.action, data)
                        .then(response => {
                            if(response.data) {
                                this.successIziToast('Роль(и) успешно сохранены');
                            } else {
                                this.whoopsIziToast('Ошибка при сохранении роли(ей)');
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