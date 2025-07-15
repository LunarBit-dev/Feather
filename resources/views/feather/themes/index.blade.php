@extends('layouts.admin')

@section('title', __('feather::themes.manage_themes'))

@section('content-header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('feather::themes.manage_themes') }}</h1>
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md"
                onclick="openUploadModal()">
            {{ __('feather::themes.upload_theme') }}
        </button>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($themes as $themeId => $theme)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Theme Preview -->
                @if(isset($theme['preview_image']))
                    <img src="{{ asset('themes/' . $themeId . '/' . $theme['preview_image']) }}" 
                         alt="{{ $theme['name'] }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">{{ __('feather::themes.no_preview') }}</span>
                    </div>
                @endif

                <!-- Theme Info -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $theme['name'] }}</h3>
                        @if($activeTheme === $themeId)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                {{ __('feather::themes.active') }}
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-2">{{ $theme['description'] }}</p>
                    
                    <div class="text-xs text-gray-500 mb-4">
                        <div>{{ __('feather::themes.version') }}: {{ $theme['version'] }}</div>
                        <div>{{ __('feather::themes.author') }}: {{ $theme['author'] }}</div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        @if($activeTheme !== $themeId)
                            <button type="button" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                                    onclick="activateTheme('{{ $themeId }}')">
                                {{ __('feather::themes.activate') }}
                            </button>
                        @endif
                        
                        @if($activeTheme !== $themeId)
                            <button type="button" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                    onclick="deleteTheme('{{ $themeId }}')">
                                {{ __('feather::themes.delete') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-5L9 9a2 2 0 00-2 2v10z" />
                    </svg>
                    <p>{{ __('feather::themes.no_themes') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">{{ __('feather::themes.upload_theme') }}</h3>
                </div>
                
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4">
                        <label for="theme_file" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('feather::themes.theme_file') }}
                        </label>
                        <input type="file" 
                               id="theme_file" 
                               name="theme_file" 
                               accept=".zip"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <p class="text-xs text-gray-500 mt-1">{{ __('feather::themes.upload_help') }}</p>
                    </div>
                    
                    <div class="px-6 py-4 border-t flex justify-end space-x-2">
                        <button type="button" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800"
                                onclick="closeUploadModal()">
                            {{ __('feather::common.cancel') }}
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            {{ __('feather::themes.upload') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
        document.getElementById('uploadForm').reset();
    }

    function activateTheme(theme) {
        if (confirm('{{ __("feather::themes.confirm_activate") }}')) {
            fetch(`{{ route('admin.themes.activate', '') }}/${theme}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("feather::common.error_occurred") }}');
            });
        }
    }

    function deleteTheme(theme) {
        if (confirm('{{ __("feather::themes.confirm_delete") }}')) {
            fetch(`{{ route('admin.themes.delete', '') }}/${theme}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("feather::common.error_occurred") }}');
            });
        }
    }

    // Handle form submission
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.themes.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("feather::common.error_occurred") }}');
        });
    });
</script>
@endpush
