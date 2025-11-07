
<header>
    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Update Photo Profile') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600">
        {{ __("Update your profile photo to keep your account up-to-date.") }}
    </p>
</header>
<form method="POST" action="{{ route('profile.update-photo') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @method('PATCH')

    <div class="flex flex-row items-center gap-3">
        <!-- Preview Gambar -->
        <div class="w-32 h-32 mb-4">
            <img id="photoPreview"
                src="{{ Auth::user()->images ? Storage::url(Auth::user()->images) : 'https://via.placeholder.com/150' }}"
                alt="Profile Photo" class="rounded-full w-full h-full object-cover border border-gray-300">

        </div>
        <div class="flex flex-col items-start justify-center">
            <label class="block">
                <span class="sr-only">Choose profile photo</span>
                <input type="file" name="photo" id="photoInput" accept="image/*"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100"/>
            </label>
            @error('photo')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror

            <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Upload Foto
            </button>
        </div>

        <!-- Input File -->
    </div>
</form>

<script>
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
