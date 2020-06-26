@extends('templates.app')
@section('title','Парсер болдов')
@section('layout')
@section('footer-scripts')
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script>
        new Vue({
            el: '#vote-parser',
            data: {
                
            },
            methods: {
                
            },
            mounted: function() {
			
            },
            created: function(){
			
            }
        });
    </script>
@endsection
<section class="container games-page" id="vote-parser">
    <div class="card custom-card">
        <div class="card-header">
            <h1 class="card-title">
                Парсер болдов
            </h1>
        </div>
        <div class="card-block table-responsive" id="players">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card custom-card">

                        <div class="card-block p-t-0">
                            <form action="{{ route('admin.game.save') }}" @submit.prevent="saveGame">
								{{ csrf_field() }}
								<div class="bordered-form page__header-row">
									<div class="form-group">
										<input class="form-control" placeholder="Ссылка" name="game_link">
									</div>
									<button type="submit" class="btn btn-primary">Парсить</button>
								</div>
							</form>
							
                        </div>
                    </div>
                </div>
            </div>
			<div class="row">
				<div class="col-sm-12">
					<table class="players-table latest-matches table dataTable no-footer" id="GamesTable" role="grid">
						<thead>
							<th>№</th>
							<th>Ник</th>
							<th>Болд</th>
							<th>Страница</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
        </div>
    </div>

</section>
@endsection