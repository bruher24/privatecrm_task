@extends('layout')
@section('title')
    Рационы
@endsection
@section('main_content')
    <div class="main-content">
        <h1>Список рационов заказа №{{$id}}</h1>
        @if($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container">
            <table class="table">
                <thead>
                <tr>
                    <th>Номер рациона</th>
                    <th>Дата готовки</th>
                    <th>Дата доставки</th>
                </tr>
                @foreach($rations as $ration)
                    <tr>
                        <td>{{$ration->id}}</td>
                        <td>{{$ration->cooking_date}}</td>
                        <td>{{$ration->delivery_date}}</td>
                    </tr>
                @endforeach
                </thead>
            </table>
            <a href="/orders"><button class="btn btn-dark">Назад</button></a>
            <a href="/"><button class="btn btn-dark">На главную</button></a>
            {{$rations->links('bootstrap-5')}}
        </div>
    </div>
@endsection
