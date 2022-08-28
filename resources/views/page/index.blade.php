<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        @include('head')
        <title>{{ config('app.name') }} / {{ __( 'index.title' ) }}</title>
    </head>
    <body>
        @include('header')

        <section class="welcome-area">
            <div class="container">
                @guest
                    <h4>Привет Гость!</h4>
                @endguest
                @auth
                    <h4>Привет {{ Auth()->user()->name }}!</h4>
                @endauth
            </div>
        </section>
    </body>
</html>
