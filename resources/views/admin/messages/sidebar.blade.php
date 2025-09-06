@php use Illuminate\Support\Str; @endphp
<div class="space-y-4">
    @foreach($messages as $message)
        @php
            $payload = [
                'id' => $message->id,
                'name' => $message->first_name.' '.$message->last_name,
                'email' => $message->email,
                'message' => $message->message,
                'date' => $message->created_at->format('M d, Y H:i'),
            ];
        @endphp
        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded">
            <p class="font-semibold">{{ $message->first_name }} {{ $message->last_name }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($message->message, 60) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->format('M d, Y H:i') }}</p>
            <div class="mt-2 flex space-x-2">
                <button type="button" data-read='@json($payload)' class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Read</button>
                <form id="delete-form-{{ $message->id }}" action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm='Delete this message?'>
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
<div class="mt-4">
    {{ $messages->links() }}
</div>

