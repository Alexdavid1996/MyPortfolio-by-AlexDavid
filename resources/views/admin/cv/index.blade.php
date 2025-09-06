@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">My CV</h1>
@if(session('status'))
    <div class="mb-4 text-green-600 dark:text-green-400">{{ session('status') }}</div>
@endif

<div class="mb-10">
    {{-- Profile --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-user-circle class="w-6 h-6 mr-1 text-brand" /> Profile
        </h2>
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $user->nationality) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('nationality')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('country')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('date_of_birth')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save Profile</button>
        </form>
    </section>
</div>

<div class="mb-10">
    {{-- Contact Email --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-envelope class="w-6 h-6 mr-1 text-brand" /> Contact Email
        </h2>
        <form method="POST" action="{{ route('admin.settings.contact-email') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                @error('contact_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Update Email</button>
        </form>
    </section>
</div>

<div class="grid md:grid-cols-2 gap-6 mb-10">
    {{-- Skills --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-academic-cap class="w-6 h-6 mr-1 text-brand" /> Skills
        </h2>
        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.skills.store') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                @csrf
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" placeholder="Skill BADGE" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('category')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level</label>
                        <select name="level" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>beginner</option>
                            <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>intermediate</option>
                            <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>advanced</option>
                            <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>expert</option>
                        </select>
                        @error('level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Years of Experience</label>
                        <input type="number" name="years_of_experience" value="{{ old('years_of_experience') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('years_of_experience')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Add Skill</button>
            </form>
            @foreach($skills as $skill)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                    <form method="POST" action="{{ route('admin.skills.update', $skill) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" value="{{ old('name', $skill->name) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <input type="text" name="category" value="{{ old('category', $skill->category) }}" placeholder="Skill BADGE" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('category')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level</label>
                                <select name="level" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="beginner" {{ old('level', $skill->level) == 'beginner' ? 'selected' : '' }}>beginner</option>
                                    <option value="intermediate" {{ old('level', $skill->level) == 'intermediate' ? 'selected' : '' }}>intermediate</option>
                                    <option value="advanced" {{ old('level', $skill->level) == 'advanced' ? 'selected' : '' }}>advanced</option>
                                    <option value="expert" {{ old('level', $skill->level) == 'expert' ? 'selected' : '' }}>expert</option>
                                </select>
                                @error('level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Years of Experience</label>
                                <input type="number" name="years_of_experience" value="{{ old('years_of_experience', $skill->years_experience) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('years_of_experience')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
                    </form>
                    <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" class="text-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline inline-flex items-center"><x-heroicon-o-trash class="w-5 h-5 mr-1" />Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Languages --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-language class="w-6 h-6 mr-1 text-brand" /> Languages
        </h2>
        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.languages.store') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level</label>
                        <select name="level" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>beginner</option>
                            <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>intermediate</option>
                            <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>advanced</option>
                            <option value="conversational" {{ old('level') == 'conversational' ? 'selected' : '' }}>conversational</option>
                            <option value="fluent" {{ old('level') == 'fluent' ? 'selected' : '' }}>fluent</option>
                            <option value="native" {{ old('level') == 'native' ? 'selected' : '' }}>native</option>
                        </select>
                        @error('level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Add Language</button>
            </form>

            @foreach($languages as $language)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                    <form method="POST" action="{{ route('admin.languages.update', $language) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" value="{{ old('name', $language->name) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level</label>
                                <select name="level" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="beginner" {{ old('level', $language->level) == 'beginner' ? 'selected' : '' }}>beginner</option>
                                    <option value="intermediate" {{ old('level', $language->level) == 'intermediate' ? 'selected' : '' }}>intermediate</option>
                                    <option value="advanced" {{ old('level', $language->level) == 'advanced' ? 'selected' : '' }}>advanced</option>
                                    <option value="conversational" {{ old('level', $language->level) == 'conversational' ? 'selected' : '' }}>conversational</option>
                                    <option value="fluent" {{ old('level', $language->level) == 'fluent' ? 'selected' : '' }}>fluent</option>
                                    <option value="native" {{ old('level', $language->level) == 'native' ? 'selected' : '' }}>native</option>
                                </select>
                                @error('level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
                    </form>
                    <form method="POST" action="{{ route('admin.languages.destroy', $language) }}" class="text-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline inline-flex items-center"><x-heroicon-o-trash class="w-5 h-5 mr-1" />Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>
</div>

<div class="mb-10">
    {{-- Experiences --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-briefcase class="w-6 h-6 mr-1 text-brand" /> Experiences
        </h2>
        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.experiences.store') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                @csrf
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('company_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <input type="text" name="role_title" value="{{ old('role_title') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('role_title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('start_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('end_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex items-center">
                    <label class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Current</label>
                    <input type="checkbox" name="is_current" value="1" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                </div>
                <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Add Experience</button>
            </form>

            @foreach($experiences as $experience)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                    <form method="POST" action="{{ route('admin.experiences.update', $experience) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">{{ $experience->company_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $experience->role_title }} @if($experience->location) - {{ $experience->location }} @endif</p>
                        </div>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $experience->company_name) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('company_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                                <input type="text" name="role_title" value="{{ old('role_title', $experience->role_title) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('role_title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <input type="text" name="location" value="{{ old('location', $experience->location) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date', optional($experience->start_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('start_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date', optional($experience->end_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                                @error('end_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Current</label>
                            <input type="checkbox" name="is_current" value="1" {{ old('is_current', $experience->is_current) ? 'checked' : '' }} class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        </div>
                        <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
                    </form>
                    <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}" class="text-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline inline-flex items-center"><x-heroicon-o-trash class="w-5 h-5 mr-1" />Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
