<div class="space-y-2" data-field="{{ $field }}" data-context="{{ $context }}" data-slug="{{ $slug ?? '' }}">
    <div class="flex items-center gap-2 p-2 bg-gray-700 text-gray-300 rounded" data-toolbar>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="formatBlock" data-value="h2">H2</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="formatBlock" data-value="h3">H3</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="formatBlock" data-value="h4">H4</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="formatBlock" data-value="h5">H5</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="bold"><strong>B</strong></button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand italic" data-command="italic">I</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="insertOrderedList">OL</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="insertUnorderedList">UL</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="createLink">Link</button>
        <button type="button" class="px-2 py-1 rounded hover:bg-gray-600 hover:text-brand" data-command="image">Img</button>
    </div>
    <div class="editor-content content-body min-h-[200px] p-2 bg-gray-700 text-gray-300 border border-gray-600 rounded focus:outline-none" contenteditable="true">{!! old($field, $content ?? '') !!}</div>
    <textarea name="{{ $field }}" class="hidden">{!! old($field, $content ?? '') !!}</textarea>
    <input type="hidden" data-token value="{{ csrf_token() }}">
</div>
<script src="{{ asset('js/simple-editor.js') }}"></script>
