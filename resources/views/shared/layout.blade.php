<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::asset('css/styles.css')}}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/jquery.arcticmodal.css')}}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/simple.css')}}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/admin.css')}}" />
    <script src="{{ URL::asset('js/jquery-3.1.1.js')}}"></script>
    <script src="{{ URL::asset('js/knockout-3.4.0.debug.js')}}"></script>
    <script src="{{ URL::asset('js/knockout.mapping.js')}}"></script>
    <script src="{{ URL::asset('js/jquery.arcticmodal.js')}}"></script>
    @yield('javascript')
</head>
<body>
    <div class="menu">
        <ul>
            <li>Главная</li>
            <li>Преподаватели</li>
            <li>Группы</li>
            <li>Студенты</li>
            <li>Дисциплины</li>
            <li>Тесты</li>
            <li>Результаты</li>
            <li>Выход</li>
        </ul>
    </div>
    @yield('content')

    <div class="footer">

    </div>
</body>
</html>