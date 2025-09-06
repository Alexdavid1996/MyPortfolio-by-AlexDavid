@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="space-y-4 bg-white dark:bg-gray-800 p-8 rounded-lg shadow text-center">
            <x-heroicon-o-exclamation-triangle class="w-16 h-16 text-yellow-500 mx-auto" />
            <h1 class="text-3xl font-extrabold text-brand">Oops! Page Not Found</h1>
            <p class="text-gray-600 dark:text-gray-300">Hey, this page was not found. Please check the sidebar menu, you might find what you are looking for there 😊</p>
        </div>
    </div>
@endsection
