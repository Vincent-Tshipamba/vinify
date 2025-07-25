<header class="flex justify-between items-center bg-white dark:bg-neutral-900 shadow-sm px-6 py-4">
    <!-- Logo -->
    <div>
        <a href="{{ route('dashboard') }}"
            class="text-2xl font-bold dark:text-[#ff0] hover:text-yellow-400 transition-all duration-150 focus:outline-none">
            Vinify
        </a>
    </div>

    <!-- Navigation + Actions -->
    <div class="flex items-center space-x-6">
        <!-- Navigation links -->
        <nav class="bg-white/10 dark:bg-neutral-800/50 backdrop-blur-md rounded-full px-6 py-2 shadow-inner">
            <ul class="flex items-center space-x-5 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard') ? 'font-bold dark:text-[#ff0]' : '' }} hover:text-yellow-500 transition-colors">Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('ai-detection') }}"
                        class="text-gray-700 dark:text-gray-300 {{ request()->routeIs('ai-detection') ? 'font-bold dark:text-[#ff0]' : '' }} hover:text-yellow-500 transition-colors">Scanner</a>
                </li>
                <li>
                    <a href="{{ route('analyses.index') }}"
                        class="text-gray-700 dark:text-gray-300 {{ request()->routeIs('analyses.index', 'analyses.show') ? 'font-bold dark:text-[#ff0]' : '' }} hover:text-yellow-500 transition-colors">Analyses</a>
                </li>
                <li>
                    <a href="{{ route('documents.index') }}"
                        class="text-gray-700 dark:text-gray-300 {{ request()->routeIs('documents.index') ? 'font-bold dark:text-[#ff0]' : '' }} hover:text-yellow-500 transition-colors">Documents</a>
                </li>
                <li>
                    <a href="{{ route('contact') }}"
                        class="text-gray-700 dark:text-gray-300 {{ request()->routeIs('contact') ? 'font-bold dark:text-[#ff0]' : '' }} hover:text-yellow-500 transition-colors">Contact</a>
                </li>
            </ul>
        </nav>

        <!-- Actions: Profile / Notifications / Settings -->
        <div class="flex items-center space-x-3">
            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
                class="bg-white/10 dark:bg-neutral-800/50 backdrop-blur-md rounded-full p-2 text-gray-600 dark:text-gray-300 hover:text-yellow-500 transition"
                title="Profil">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </a>

            <!-- Notifications -->
            <a href="#"
                class="bg-white/10 dark:bg-neutral-800/50 backdrop-blur-md rounded-full p-2 text-gray-600 dark:text-gray-300 hover:text-yellow-500 transition"
                title="Notifications">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.556 8.5h8m-8 3.5H12m7.111-7H4.89a.9.9 0 0 0-.89.875v9.25c0 .483.393.875.875.875H9l3 4 3-4h4.111a.9.9 0 0 0 .889-.875v-9.25a.9.9 0 0 0-.889-.875Z" />
                </svg>
            </a>

            <!-- Settings -->
            <a href="{{ route('admin.roles-permissions') }}"
                class="bg-white/10 dark:bg-neutral-800/50 backdrop-blur-md rounded-full px-3 py-2 flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-yellow-500 transition"
                title="Paramètres">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-linejoin="round"
                        d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span class="hidden sm:inline">Paramètres</span>
            </a>
        </div>
    </div>
</header>
