<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    @include('head')
    <title>{{ config('app.name') }} / {{ __( 'header.auth.login' ) }}</title>
</head>
<body style="overflow-x: hidden;">
<div id="preloader">
    <div class="loader"></div>
</div>
@include('header')
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Войти на сайт</h3>
                </div>
                <div class="panel-body">
                    <form accept-charset="UTF-8" role="form" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" placeholder="yourName" name="name" type="text" value="{{ old('name') }}">
                            @error('name')
                            <p>{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Пароль" name="password" type="password" value="">
                            @error('password')
                            <p>{{ $message }}</p>
                            @enderror
                        </div>
                        <input class="btn btn-lg btn-primary btn-block lfd" type="submit" value="Войти">
                        <a href="{{ route('auth.register') }}" class="text-primary btn-block lfd">Нет аккаунта?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
