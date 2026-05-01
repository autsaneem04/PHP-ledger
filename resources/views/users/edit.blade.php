<x-app-layout>

    <div class="p-6 max-w-xl center">

        <h2 class="text-xl text-white font-bold mb-4">Edit User</h2>

        @if ($errors->any())
            <div class="bg-red-200 text-red-800 p-3 mb-4 rounded" id="error_msg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}">

            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-800 dark:text-gray-200">Name</label>

                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="border p-2 w-full rounded
@error('name') border-red-500 bg-red-100 @enderror">

                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-4">
                <label class="block text-gray-800 dark:text-gray-200">Username</label>

                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                    class="border p-2 w-full rounded
@error('username') border-red-500 bg-red-100 @enderror">

                @error('username')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-4">

                <label class="block text-gray-800 dark:text-gray-200">
                    Password
                </label>

                <input type="password" name="password" placeholder="Leave blank if not change"
                    class="border p-2 w-full rounded
@error('password') border-red-500 bg-red-100 @enderror">

                @error('password')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

            </div>


            <div class="mb-4">
                <label class="block text-gray-800 dark:text-gray-200">Email</label>

                <input type="text" name="email" value="{{ old('email', $user->email) }}"
                    class="border p-2 w-full rounded
@error('email') border-red-500 bg-red-100 @enderror">

                @error('email')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

            </div>


            <div class="mb-4">
                <label class="block text-gray-800 dark:text-gray-200">User Group</label>

                <select name="group_user_id"
                    class="border p-2 w-full rounded
@error('group_user_id') border-red-500 bg-red-100 @enderror">

                    <option value="">-</option>

                    @foreach ($groups as $g)
                        <option value="{{ $g->group_user_id }}" @selected(old('group_user_id', $user->group_user_id) == $g->group_user_id)>
                            {{ $g->group_user_name }}
                        </option>
                    @endforeach

                </select>

                @error('group_user_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

            </div>


            <div class="mb-4">
                <label class="block text-gray-800 dark:text-gray-200">Status</label>

                <select name="is_enable"
                    class="border p-2 w-full rounded
@error('is_enable') border-red-500 bg-red-100 @enderror">

                    <option value="1" @selected(old('is_enable', $user->is_enable) == 1)>
                        Enable
                    </option>

                    <option value="0" @selected(old('is_enable', $user->is_enable) == 0)>
                        Disable
                    </option>

                </select>

                @error('is_enable')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

            </div>


            <div class="flex gap-2 mt-4">

                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-24 text-center">
                    Save
                </button>

                <a href="{{ route('users.index') }}"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded w-24 text-center">
                    Cancel
                </a>

            </div>

        </form>

    </div>


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const error = document.getElementById("error_msg");

                if (error) {
                    setTimeout(() => {
                        error.style.display = "none";
                    }, 5000);
                }

            });
        </script>
    @endpush

</x-app-layout>
