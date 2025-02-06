@extends('layout')
@section('title')
    Заказы
@endsection
@section('main_content')
    <div class="main-content">
        <h1>Список заказов</h1>
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
                    <th>Номер заказа</th>
                    <th>Имя заказчика</th>
                    <th>Номер телефона</th>
                    <th>ID тарифа</th>
                    <th>Тип расписания</th>
                    <th>Комментарий</th>
                    <th>Дата создания</th>
                    <th>Первая доставка</th>
                    <th>Последняя доставка</th>
                    <th></th>
                </tr>
                @foreach($orders as $order)
                    <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->client_name}}</td>
                        <td>{{$order->client_phone}}</td>
                        <td>{{$order->tariff_id}}</td>
                        <td>{{$order->schedule_type}}</td>
                        <td>{{$order->comment}}</td>
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->first_date}}</td>
                        <td>{{$order->last_date}}</td>
                        <td><a class="link-info" href="/order/{{$order->id}}">Рационы</a></td>
                    </tr>
                @endforeach
                </thead>
            </table>
            <a href="/"><button class="btn btn-dark">На главную</button></a>
            {{$orders->links('bootstrap-5')}}
        </div>
    </div>
@endsection
