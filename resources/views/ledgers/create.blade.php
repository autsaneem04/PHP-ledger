<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
                    Add Ledger
                </h2>

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-200 text-red-800 p-3 mb-4 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('ledgers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- TYPE --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Type</label>
                            <select name="type"
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">
                                <option value="income">Income</option>
                                <option value="expense" selected>Expense</option>
                            </select>
                        </div>

                        {{-- AMOUNT --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Amount</label>
                            <input type="number" step="0.01" name="amount"
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full"
                                required>
                        </div>

                        {{-- USER (เฉพาะ super user) --}}
                        @if (auth()->user()?->isSuperUser())
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">User</label>
                                <select name="user_id"
                                    class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- IMAGE --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Image</label>
                            <input type="file" name="image"
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">
                        </div>

                    </div>

                    {{-- NOTE --}}
                    <div class="mt-4">
                        <label class="block text-sm text-gray-300 mb-1">Note</label>
                        <textarea name="note" rows="3"
                            class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full"></textarea>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3 mt-6">

                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded w-28">
                            Save
                        </button>

                        <a href="{{ route('ledgers.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded w-28 text-center">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
