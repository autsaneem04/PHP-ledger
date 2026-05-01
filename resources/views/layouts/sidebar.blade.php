<div class="flex h-screen bg-gray-100 dark:bg-gray-900">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white shadow">

        <div class="p-4 text-xl font-bold border-b border-gray-700">
            My App
        </div>

        <nav class="mt-4 text-white" style="color: white">

            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 text-white hover:bg-gray-700 transition">
                Dashboard
            </a>

            @if(auth()->user()->isSuperUser())
                <a href="{{ route('group_users.index') }}"
                   class="block px-4 py-2 text-white hover:bg-gray-700 transition">
                    Group Users
                </a>
            @endif

        </nav>

    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

        <!-- Top Bar -->
        <div class="bg-white dark:bg-gray-800 shadow p-4 flex justify-end">

            <div class="text-gray-800 dark:text-gray-200">
                {{ Auth::user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}" class="ml-4">
                @csrf
                <button class="text-red-500 hover:text-red-700">
                    Logout
                </button>
            </form>

        </div>

        <!-- Page Content -->
        <div class="p-6">

            {{ $slot }}

        </div>

    </div>

</div>
