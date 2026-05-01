<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
                    Import CSV
                </h2>

                {{-- ✅ SUCCESS --}}
                @if (session('success'))
                    <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ❌ VALIDATION ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-200 text-red-800 p-3 mb-4 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ⚠️ IMPORT ERROR --}}
                @if (session('import_errors'))
                    <div class="bg-yellow-200 text-yellow-800 p-3 mb-4 rounded max-h-40 overflow-y-auto">
                        <strong>บางรายการผิด:</strong>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach (session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('ledgers.import.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- 📁 FILE --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-2">
                            Choose CSV file
                        </label>

                        <input type="file" name="fileCSV" accept=".csv"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded px-3 py-2" required>
                    </div>

                    {{-- 📌 HINT --}}
                    <div class="mt-3 text-sm text-gray-400">
                        <p>รูปแบบไฟล์ CSV:</p><a href="{{ route('import.csv_template') }}"
                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded mt-2">
                            ⬇ Download CSV Template
                        </a>
                        <code class="block bg-gray-900 text-green-400 p-2 rounded mt-1">
                            type,amount,note<br>
                            income,1000,Salary<br>
                            expense,200,Food
                        </code>
                    </div>

                    {{-- 🔘 BUTTON --}}
                    <div class="flex justify-end gap-3 mt-6">

                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded w-28">
                            Import
                        </button>

                        <a href="{{ route('ledgers.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded w-28 text-center">
                            Back
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
