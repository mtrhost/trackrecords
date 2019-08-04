@extends('layouts.admin')

@section('content')
<section class="bdy content reset cflex">
    <div class="c100 blk __content-mount">
        <div class="container-fluid admin-container">
            <div class="row">
                Привет, {{ Auth::user()->name }}
            </div>
        </div>
    </div>
</section>
@endsection