@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="space-y-4 bg-white dark:bg-gray-800 p-8 rounded-lg shadow text-center">
            <x-heroicon-o-x-circle class="w-16 h-16 text-red-500 mx-auto" />
            <h1 class="text-3xl font-extrabold text-brand">Oops! Something went wrong</h1>
            <p class="text-gray-600 dark:text-gray-300">Hey, something went wrong. Please check the sidebar menu, you might find what you are looking for there 😊</p>
        </div>
    </div>
@endsection
