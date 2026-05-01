<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between mb-4">

                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Group Users</h2>

                        <a href="{{ route('group_users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">
                            Add
                        </a>

                    </div>

                    <table class="table-auto w-full border border-gray-300 text-center text-gray-800 dark:text-gray-200">

                        <thead class="bg-gray-100 dark:bg-gray-700">

                            <tr>
                                <th class="border p-2">ID</th>
                                <th class="border p-2">Name</th>
                                <th class="border p-2">Super</th>
                                <th class="border p-2">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($groups as $g)
                                <tr
                                    class="odd:bg-white border even:bg-gray-100 hover:bg-blue-100 dark:odd:bg-gray-800 dark:even:bg-gray-700 dark:hover:bg-gray-600 transition">


                                    <td class=" border  p-2" style="text-align: center">{{ $g->group_user_id }}</td>

                                    <td class=" border  p-2">
                                        {{ $g->group_user_name }}
                                    </td>

                                    <td class=" border  p-2">
                                        {{ $g->is_super_user ? 'Yes' : 'No' }}
                                    </td>

                                    <td class="border p-2" style="width:200px">

                                        <div class="flex justify-center gap-2">

                                            <a href="{{ route('group_users.edit', $g) }}"
                                                class="bg-blue-500 text-white px-4 py-2 rounded w-24 text-center">
                                                Edit
                                            </a>

                                            <form action="{{ route('group_users.destroy', $g) }}" method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button onclick="return confirmDelete()"
                                                    class="bg-red-500 text-white px-4 py-2 rounded w-24">
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete() {
                return confirm("Delete this group user?");
            }
        </script>
    @endpush
</x-app-layout>
