<div class="mt-3 space-y-1 text-left text-xs">
    <p class="text-gray-500 dark:text-gray-400 break-all">
        <span class="font-medium text-gray-700 dark:text-gray-300">Email:</span>
        {{ $settings?->contact_email ?? 'Not provided' }}
    </p>
    <p class="text-gray-500 dark:text-gray-400 break-words">
        <span class="font-medium text-gray-700 dark:text-gray-300">Nationality:</span>
        {{ $user?->nationality ?? 'Not set' }}
    </p>
    <p class="text-gray-500 dark:text-gray-400 break-words">
        <span class="font-medium text-gray-700 dark:text-gray-300">Country:</span>
        {{ $user?->country ?? 'Not set' }}
    </p>
    <p class="text-gray-500 dark:text-gray-400 break-words">
        <span class="font-medium text-gray-700 dark:text-gray-300">Birthdate:</span>
        {{ $user?->date_of_birth?->format('F j, Y') ?? 'Not set' }}
    </p>
</div>
