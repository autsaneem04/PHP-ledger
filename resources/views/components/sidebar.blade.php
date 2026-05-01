<div class="hidden md:block w-64 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 min-h-screen border-r border-gray-100 dark:border-gray-700 transition-colors duration-200">

    <!-- Logo -->
    <div class="px-4 py-4 text-xl font-bold border-b border-gray-100 dark:border-gray-700">
        MyApp
    </div>

    <!-- Toggle Menu -->
    <div x-data="{ open: true }">
        <button @click="open = !open" class="w-full text-left px-4 py-2 font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
            Menu
        </button>

        <!-- Sub Menu -->
        <div x-show="open" class="mt-1 text-sm space-y-1">

            <a href="{{ route('dashboard') }}"
                class="block pl-8 pr-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
           {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold border-r-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400' }}">
                Dashboard
            </a>
            <a href="{{ route('ledgers.index') }}"
                class="block pl-8 pr-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
           {{ request()->routeIs('ledgers.*') ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold border-r-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400' }}">
                Ledgers
            </a>

        </div>
    </div>
    
    <!-- Config Menu -->
    <div x-data="{ open: true }" class="mt-4">
        <button @click="open = !open" class="w-full text-left px-4 py-2 font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
            Config
        </button>

        <!-- Sub Menu -->
        <div x-show="open" class="mt-1 text-sm space-y-1">

            @if (auth()->user()->isSuperUser())
                <a href="{{ route('group_users.index') }}"
                    class="block pl-8 pr-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
           {{ request()->routeIs('group_users.*') ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold border-r-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400' }}">
                    Group Users
                </a>
                <a href="{{ route('users.index') }}"
                    class="block pl-8 pr-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
           {{ request()->routeIs('users.*') ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-semibold border-r-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400' }}">
                    Users
                </a>
            @endif

        </div>
    </div>

</div>
