<x-app-layout>

    <div class="p-6 max-w-xl">

        <h2 class="text-xl font-bold mb-4">Edit Group User</h2>

        <form method="POST" action="{{ route('group_users.update', $group_user) }}">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="block text-gray-800 dark:text-gray-200">
                    Group Name
                </label>

                <input type="text" name="group_user_name" value="{{ $group_user->group_user_name }}"
                    class="border p-2 w-full rounded" required>

            </div>

            <div class="mb-4">

                <label class="block text-gray-800 dark:text-gray-200">
                    Super User
                </label>

                <select name="is_super_user" class="border p-2 w-full rounded">

                    <option value="0" {{ $group_user->is_super_user ? '' : 'selected' }}>
                        No
                    </option>

                    <option value="1" {{ $group_user->is_super_user ? 'selected' : '' }}>
                        Yes
                    </option>

                </select>

            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Update
            </button>

            <a href="{{ route('group_users.index') }}" class="ml-2 text-gray-600">
                Cancel
            </a>

        </form>

    </div>

</x-app-layout>
