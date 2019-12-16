@extends('templates.app')
@section('title','Ачивки')
@section('layout')
<section class="achievements">
    <div class="card card-danger custom-card">
        <div class="card-header">
            <h1 class="card-title">Зал достижений</h1>
        </div>
        <div class="card-block p-y-0">
            <table class="table achievements__header-cells">
                <tbody>
                    @foreach($achievements as $achievement)
                        <tr>
                            <td class="align-middle">
                                <img class="achievements__main-image" src="{{ $achievement->image_original }}" alt="{{ $achievement->name }}">
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
    </div>
</section>
@endsection