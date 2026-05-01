<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 🔍 SEARCH BOX --}}
            <div x-data="{ open: true }" class="space-y-2">

                <div x-show="open" x-transition class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                    <h2 class="text-lg font-bold mb-2 text-gray-800 dark:text-gray-200">
                        Search
                    </h2>

                    <hr class="mb-4">

                    <form method="GET">
                        <div class="flex flex-col gap-4">

                            {{-- 🔍 FILTER (ซ้ายบน) --}}
                            <div class="flex justify-start gap-4 flex-wrap">

                                {{-- FROM --}}
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">From</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded px-3 py-2 w-40">
                                </div>

                                {{-- TO --}}
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">To</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded px-3 py-2 w-40">
                                </div>

                                {{-- TYPE --}}
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Type</label>
                                    <select name="type"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded px-3 py-2 w-40">
                                        <option value="">All</option>
                                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>
                                            Income
                                        </option>
                                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>
                                            Expense
                                        </option>
                                    </select>
                                </div>

                                {{-- USER (เฉพาะ super user) --}}
                                @if (auth()->user()?->groupUser?->is_super_user)
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">User</label>
                                        <select name="user_id"
                                            class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded px-3 py-2 w-44">
                                            <option value="">All</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            </div>

                            {{-- 🔘 BUTTON (ขวาล่าง) --}}
                            <div class="flex justify-end gap-3">

                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded w-28">
                                    Search
                                </button>

                                <a href="{{ route('ledgers.index') }}"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded w-28 text-center">
                                    Reset
                                </a>

                            </div>

                        </div>
                    </form>

                </div>

                {{-- 🔽 Toggle --}}
                <div class="flex justify-end">
                    <button @click="open = !open" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        <span x-show="open">▲</span>
                        <span x-show="!open">▼</span>
                    </button>
                </div>

            </div>

            {{-- 📊 DATA TABLE --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <div class="flex justify-between mb-4">

                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        Income and Expenses
                    </h2>

                    <div class="flex gap-2"> <!-- gap-2 คือระยะห่างระหว่างปุ่ม -->
                        <a href="{{ route('ledgers.import') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Import
                        </a>

                        <a href="{{ route('ledgers.create') }}"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Add
                        </a>
                    </div>


                </div>

                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600 text-center text-gray-800 dark:text-gray-200">

                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>

                            <th class="border border-gray-300 dark:border-gray-600 p-2">Type test</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Amount</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Date</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">User</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Note</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ledgers as $l)
                            <tr class="hover:bg-blue-100 dark:hover:bg-gray-600">



                                {{-- TYPE --}}
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <span class="{{ $l->type == 'income' ? 'text-green-600 dark:text-green-400 font-bold' : 'text-red-600 dark:text-red-400 font-bold' }}">
                                        {{ ucfirst($l->type) }}
                                    </span>
                                </td>

                                {{-- AMOUNT --}}
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ number_format($l->amount, 2) }}
                                </td>

                                {{-- DATE --}}
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $l->created_at->format('d-m-Y H:i:s') }}
                                </td>

                                {{-- USER --}}
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $l->user?->name ?? '-' }}
                                </td>

                                {{-- NOTE --}}
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-left">
                                    {{ $l->note }}
                                </td>

                                {{-- ACTION --}}
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('ledgers.show', $l) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                            View
                                        </a>

                                        <a href="{{ route('ledgers.edit', $l) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                            Edit
                                        </a>

                                        <form action="{{ route('ledgers.destroy', $l) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Delete?')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-gray-500">
                                    No data found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                {{-- 📄 PAGINATION --}}
                <div class="mt-4">
                    {{ $ledgers->links() }}
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
