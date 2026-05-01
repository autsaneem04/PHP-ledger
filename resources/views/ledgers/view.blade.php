<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
                    View Ledger
                </h2>

                {{-- SUCCESS --}}
                @if (session('success'))
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
                <form enctype="multipart/form-data">
                    @csrf


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- TYPE --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Type</label>
                            <input type="text" name="id" value="{{ $ledger->type }}" readonly
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">
                        </div>

                        {{-- AMOUNT --}}
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Amount</label>
                            <input type="number" step="0.01" name="amount"
                                value="{{ old('amount', $ledger->amount) }}"
                                class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full" readonly>
                        </div>

                        {{-- USER (เฉพาะ super user) --}}
                        @if (auth()->user()?->isSuperUser())
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">User</label>

                                <input type="text" name="user_id" value="{{ $ledger->user->name ?? '' }}" readonly
                                    class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full">
                            </div>
                        @endif



                    </div>

                    {{-- 🖼 รูปเดิม --}}
                    @if ($ledger->image)
                        <div class="mt-4">
                            <label class="block text-sm text-gray-300 mb-1">Current Image</label>
                            <img src="{{ asset('storage/' . $ledger->image) }}"
                                class="w-32 h-32 object-cover rounded border" id="myImg" alt="Ledger Image">
                        </div>
                    @endif
                    <div id="myModal" class="modal">
                        <span class="close">&times;</span>
                        <img class="modal-content" id="img01">
                        <div id="caption"></div>
                    </div>


                    {{-- NOTE --}}
                    <div class="mt-4">
                        <label class="block text-sm text-gray-300 mb-1">Note</label>
                        <textarea name="note" rows="3" class="bg-gray-700 border border-gray-600 text-white rounded px-3 py-2 w-full"
                            readonly>{{ old('note', $ledger->note) }}</textarea>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3 mt-6">



                        <a href="{{ route('ledgers.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded w-28 text-center">
                            Back
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>

    {{-- 🔥 PREVIEW SCRIPT --}}
    @push('styles')
        <style>
            .modal {
                display: none;
                /* Hidden by default */
                position: fixed;
                /* Stay in place */
                z-index: 1000;
                /* Sit on top */
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.9);
                /* Black background with opacity */
            }

            .modal-content {
                margin: auto;
                display: block;
                max-width: 80%;
                max-height: 80%;
                margin-top: 1%;
            }

            .close {
                position: absolute;
                top: 15px;
                right: 35px;
                color: #fff;
                font-size: 40px;
                cursor: pointer;
            }
        </style>
    @endpush
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
            const modal = document.getElementById("myModal");
            const img = document.getElementById("myImg");
            const modalImg = document.getElementById("img01");
            const captionText = document.getElementById("caption");

            // Open modal on click
            img.onclick = function() {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }

            // Close modal when clicking 'X'
            document.querySelector(".close").onclick = function() {
                modal.style.display = "none";
            }
        </script>
    @endpush

</x-app-layout>
