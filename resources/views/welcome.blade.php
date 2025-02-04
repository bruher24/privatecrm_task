@extends('layout')
@section('title')
    Главная
@endsection
@section('main_content')
    <div class="main-content">
        <h1>Форма создания заказа</h1>
        @if($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form-control" method="post" action="/make_order">
            @csrf
            <label class="form-label" for="name">ФИО
                <input class="form-control" type="text" id="name" name="name" placeholder="Иванов Иван Иванович">
            </label>
            <label class="form-label" for="phone">Номер телефона
                <input class="form-control" type="tel" id="phone" name="phone" pattern="\d{11}" required
                       placeholder="79991112233">
            </label>
            <label class="form-label" for="tariff">Тариф
                <select class="form-control" id="tariff" name="tariff">
                    <option value="1">Свежее</option>
                    <option value="2">Вчерашнее</option>
                </select>
            </label>
            <label class="form-label" for="schedule">Расписание
                <select class="form-control" id="schedule" name="schedule">
                    <option value="every_day">Ежедневно</option>
                    <option value="every_other_day">Через день на 1 день</option>
                    <option value="every_other_day_twice">Через день на 2 дня</option>
                </select>
            </label>
            <div id="date-select">
                <table class="table table-sm" id="dateTable">
                    <thead>
                    <tr>
                        <th>Дата начала</th>
                        <th>Дата окончания</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 20%"><label>
                                <input type="date" name="first_date[]" required>
                            </label></td>
                        <td><label>
                                <input type="date" name="last_date[]" required>
                            </label></td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary btn-dark" type="button" onclick="addRow()">Добавить строку</button>
            </div>
            <label for="comment"></label><textarea class="form-control" id="comment" name="comment" autocomplete="off" cols="40" rows="5"
                                                   placeholder="Комментарий к заказу..."></textarea>
            <input class="btn btn-primary btn-dark" type="submit" id="submit_btn" name="submit_btn">
        </form>
        <a href="/orders"><button class="btn btn-dark">Список заказов</button></a>
        <script>
            function addRow() {
                const table = document.getElementById('dateTable').getElementsByTagName('tbody')[0];
                const newRow = table.insertRow();

                const cell1 = newRow.insertCell(0);
                const cell2 = newRow.insertCell(1);

                const input1 = document.createElement('input');
                input1.type = 'date';
                input1.name = 'first_date[]';
                input1.required = true;
                cell1.appendChild(input1);

                const input2 = document.createElement('input');
                input2.type = 'date';
                input2.name = 'last_date[]';
                input2.required = true;
                cell2.appendChild(input2);
            }
        </script>
    </div>
@endsection
