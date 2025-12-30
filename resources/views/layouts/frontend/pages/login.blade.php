@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />Willkommen bei Pokergiants.de<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Login<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card one-card one-card-33 login">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div class="my-auth-forms">
            <label class="block font-medium text-sm text-gray-700" for="email">
                Deine E-Mail-Adresse
            </label>
            <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" id="email" type="email" name="email" required="required" autofocus="autofocus" autocomplete="username">
        </div>

        <!-- Password -->
        <div class="mt-4 my-auth-forms">
            <label class="block font-medium text-sm text-gray-700" for="password">
                Dein Passwort
            </label>

            <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" id="password" type="password" name="password" required="required" autocomplete="current-password">

        </div>

        <!-- Remember Me -->
        <div class="block mt-4 my-auth-forms">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2">Login speichern</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4 my-auth-forms">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="https://pokergiants.kighlander.de/forgot-password">
                Passwort vergessen?
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ms-3">
                Log in
            </button>
        </div>
    </form>
</div>
@endsection