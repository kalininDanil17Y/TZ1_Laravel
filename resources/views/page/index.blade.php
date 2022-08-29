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
                <div class="row">
                    <div class="col-md-12">
                        @guest
                            <h4>Привет Гость!</h4>
                        @endguest
                        @auth
                            <h4>Привет {{ Auth()->user()->name }}!</h4>
                        @endauth
                    </div>

                    <div class="col-md-6">
                        <h3>GET {{ route('api.getUsers') }}</h3>
                        <p id="dataUsers"></p>
                    </div>
                    <div class="col-md-6">
                        <h3>GET {{ route('api.getUsersLogged') }}</h3>
                        <p id="getUsersLogged"></p>
                    </div>
                    <div class="col-md-6">
                        <h3>GET {{ route('api.getUsersNoTrophy') }}</h3>
                        <p id="getUsersNoTrophy"></p>
                    </div>
                    @auth<div class="col-md-6">
                        <h3>POST {{ route('api.getSumTrophy') }}</h3>
                        <p id="getSumTrophy"></p>
                    </div>

                    <div class="col-md-12">
                        <button id="addTrophy" class="btn btn-primary">Добавить трофей</button>
                    </div>
                    @endauth
                </div>

            </div>
        </section>
    </body>
</html>
