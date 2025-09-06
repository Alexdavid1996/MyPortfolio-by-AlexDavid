@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Portfolio Items</h1>
    <div class="mb-4">
        <a href="{{ route('admin.portfolio.create') }}" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">New Item</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($portfolios as $item)
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow flex flex-col justify-between">
                <div>
                    <img src="{{ $item->thumbnail_url ? asset($item->thumbnail_url) : asset('images/placeholder-1200x630.svg') }}" alt="{{ $item->title }}" class="mb-2 w-full h-32 object-cover rounded">
                    <h2 class="font-semibold text-lg">{{ $item->title }}</h2>
                    <p class="text-sm mt-1">
                        <span class="px-2 py-0.5 rounded text-white {{ $item->status === 'published' ? 'bg-green-500' : 'bg-gray-500' }}">{{ ucfirst($item->status) }}</span>
                        @if($item->published_at)
                            <span class="ml-2 text-xs text-gray-500">{{ $item->published_at->format('M d, Y') }}</span>
                        @endif
                    </p>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.portfolio.edit', $item) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit</a>
                    <form action="{{ route('admin.portfolio.destroy', $item) }}" method="POST" data-confirm="Delete this item?">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
