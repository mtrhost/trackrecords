@extends('templates.app')
@section('title','Ачивки')
@section('layout')
<section class="container-fluid achievements">
    <div class="row">
        <div class="col-sm-4">
            <h1 class="h2">Зал достижений</h1>
        </div>
    </div>
    <div class="row data-block table-responsive">
            <table class="table table-bordered achievements__header-cells">
                <tbody>
                    @foreach($achievements as $achievement)
                        <tr>
                            <td class="align-middle">
                                <img class="achievements__main-image img-thumbnail-dark" src="{{ $achievement->image_original }}" alt="{{ $achievement->name }}">
                            </td>
                            <td class="align-middle">
                                {{ $achievement->name }}
                            </td>
                            <td class="align-middle">
                                {{ $achievement->condition }}
                            </td>
                            <td class="align-middle">
                                {{ $achievement->description }}
                            </td>
                            <td class="align-middle">
                                @foreach($achievement->players as $index => $player)
                                    <span>
                                        <a href="{{ route('player.details', $player->id) }}">
                                            {{ $player->name }}
                                        </a>
                                        @if($index < count($achievement->players) - 1)
                                            <span>, </span>
                                        @endif
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</section>
@endsection