<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
                    Edit Ledger
                </h2>

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

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

                {{-- ✅ FORM --}}
                <form method="POST" action="{{ route('ledgers.update', $ledger) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- TYPE --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Type</label>
                            <select name="type"
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">

                                <option value="income"
                                    {{ old('type', $ledger->type) == 'income' ? 'selected' : '' }}>
                                    Income
                                </option>

                                <option value="expense"
                                    {{ old('type', $ledger->type) == 'expense' ? 'selected' : '' }}>
                                    Expense
                                </option>

                            </select>
                        </div>

                        {{-- AMOUNT --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Amount</label>
                            <input type="number" step="0.01" name="amount"
                                value="{{ old('amount', $ledger->amount) }}"
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
                                        <option value="{{ $u->id }}"
                                            {{ old('user_id', $ledger->user_id) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        @endif

                        {{-- IMAGE --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Image</label>
                            <input type="file" name="image"
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full"
                                onchange="previewImage(event)">
                        </div>

                    </div>

                    {{-- 🖼 รูปเดิม --}}
                    @if ($ledger->image)
                        <div class="mt-4">
                            <label class="block text-sm text-gray-300 mb-1">Current Image</label>
                            <img src="{{ asset('storage/' . $ledger->image) }}"
                                class="w-32 h-32 object-cover rounded border">
                        </div>
                    @endif

                    {{-- 🖼 preview รูปใหม่ --}}
                    <div class="mt-4 hidden" id="preview-box">
                        <label class="block text-sm text-gray-300 mb-1">New Image Preview</label>
                        <img id="preview-img" class="w-32 h-32 object-cover rounded border">
                    </div>

                    {{-- NOTE --}}
                    <div class="mt-4">
                        <label class="block text-sm text-gray-300 mb-1">Note</label>
                        <textarea name="note" rows="3"
                            class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">{{ old('note', $ledger->note) }}</textarea>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3 mt-6">

                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded w-28">
                            Update
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

    {{-- 🔥 PREVIEW SCRIPT --}}
    @push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const previewBox = document.getElementById('preview-box');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                previewImg.src = URL.createObjectURL(input.files[0]);
                previewBox.classList.remove('hidden');
            }
        }
    </script>
    @endpush

</x-app-layout>
