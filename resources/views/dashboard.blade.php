<x-app-layout>
    <div class="">

        <h1 class="text-3xl sm:text-4xl font-light text-gray-800 dark:text-gray-200 mb-2 transition-all duration-300">
            Bienvenue, <span class="text-[#ff0] ">{{ auth()->user()->name }}</span> 👋
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">
            Heureux de vous revoir sur <span class="font-semibold text-[#ff0]">Vinify</span>. N'oubliez pas de vérifier
            les derniers rapports ou documents.
        </p>
        {{-- <time>Mar 10, 2020</time> --}}
    </div>

    <div class="flex items-center justify-between mt-6">
        <div>
            <div class="ldBar" style="width:100%;height:60px",
                data-stroke="data:ldbar/res,gradient(0,1,#9df,#9fd,#df9,#fd9)",
                data-path="M10 20Q20 15 30 20Q40 25 50 20Q60 15 70 20Q80 25 90 20"></div>
        </div>
        <div class="flex flex-wrap items-start gap-6">
            <!-- Stat block -->
            @foreach ([['route' => 'users.index', 'permission' => 'view users', 'title' => 'Users', 'value' => $users, 'icon' => 'users'], ['route' => 'analyses.index', 'permission' => 'view analyses', 'title' => 'Analyse', 'value' => $nbrAnalyses, 'icon' => 'analyse'], ['route' => 'documents.index', 'permission' => 'view documents', 'title' => 'Fichiers', 'value' => $nbrDocuments, 'icon' => 'file']] as $stat)
                <div class="flex flex-col items-start min-w-[120px] space-y-1">

                    @can($stat['permission'])
                        <div class="flex items-center gap-2">
                            <a href="{{ route($stat['route']) }}"
                                class="bg-white/10 dark:bg-neutral-800/50 backdrop-blur-md rounded-full p-2 text-gray-600 dark:text-gray-300 hover:text-yellow-500 transition"
                                title="{{ $stat['title'] }}">

                                @if ($stat['icon'] === 'users')
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                @elseif($stat['icon'] === 'analyse')
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 3v4a1 1 0 0 1-1 1H5m8 7.5 2.5 2.5M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Zm-5 9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                    </svg>
                                @elseif($stat['icon'] === 'file')
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                            d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                    </svg>
                                @endif
                            </a>
                            <span
                                class="text-3xl sm:text-4xl font-light text-gray-600 dark:text-gray-300">{{ $stat['value'] }}</span>
                        </div>
                        <span class="text-sm font-light text-gray-600 dark:text-gray-300">{{ $stat['title'] }}</span>
                    @endcan
                </div>
            @endforeach
        </div>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">

        <!--
        <article
            class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl  min-h-[410px]">

            {{-- <!-- Image --> --}}
            <img src="https://img.freepik.com/photos-gratuite/homme-noir-posant_23-2148171684.jpg" alt="User photo"
                class="absolute inset-0 h-full w-full object-cover">

            {{-- <!-- Gradient overlay pour améliorer lisibilité du bas --> --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

            {{-- <!-- Conteneur avec blur en bas --> --}}
            <div class="relative z-10 backdrop-blur-sm bg-black/20 bg-opacity-40 p-4 ">
                <h3 class="text-3xl sm:text-4xl font-light text-white">
                    {{ auth()->user()->name }}
                </h3>
                <div class="text-sm leading-6 text-gray-300">Super Admin</div>
            </div>
        </article>
        -->
        @can('view users')
            <x-userchartline :users="$users" />
        @endcan
        @can('view analyses')
            <x-analyseschartline :nbrAnalyses="$nbrAnalyses" :percentageCriticalPlagiarism="$percentageCriticalPlagiarism" />
        @endcan
        @can('view documents')
            <x-fichiersChartline :nbrDocuments="$nbrDocuments" />
        @endcan
    </div>

    <footer class="relative overflow-hidden bg-neutral-900">
        <svg class="absolute -bottom-20 start-1/2 w-[1900px] transform -translate-x-1/2" width="2745" height="488"
            viewBox="0 0 2745 488" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0.5 330.864C232.505 403.801 853.749 527.683 1482.69 439.719C2111.63 351.756 2585.54 434.588 2743.87 487"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 308.873C232.505 381.81 853.749 505.692 1482.69 417.728C2111.63 329.765 2585.54 412.597 2743.87 465.009"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 286.882C232.505 359.819 853.749 483.701 1482.69 395.738C2111.63 307.774 2585.54 390.606 2743.87 443.018"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 264.891C232.505 337.828 853.749 461.71 1482.69 373.747C2111.63 285.783 2585.54 368.615 2743.87 421.027"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 242.9C232.505 315.837 853.749 439.719 1482.69 351.756C2111.63 263.792 2585.54 346.624 2743.87 399.036"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 220.909C232.505 293.846 853.749 417.728 1482.69 329.765C2111.63 241.801 2585.54 324.633 2743.87 377.045"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 198.918C232.505 271.855 853.749 395.737 1482.69 307.774C2111.63 219.81 2585.54 302.642 2743.87 355.054"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 176.927C232.505 249.864 853.749 373.746 1482.69 285.783C2111.63 197.819 2585.54 280.651 2743.87 333.063"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 154.937C232.505 227.873 853.749 351.756 1482.69 263.792C2111.63 175.828 2585.54 258.661 2743.87 311.072"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 132.946C232.505 205.882 853.749 329.765 1482.69 241.801C2111.63 153.837 2585.54 236.67 2743.87 289.082"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 110.955C232.505 183.891 853.749 307.774 1482.69 219.81C2111.63 131.846 2585.54 214.679 2743.87 267.091"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 88.9639C232.505 161.901 853.749 285.783 1482.69 197.819C2111.63 109.855 2585.54 192.688 2743.87 245.1"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 66.9729C232.505 139.91 853.749 263.792 1482.69 175.828C2111.63 87.8643 2585.54 170.697 2743.87 223.109"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 44.9819C232.505 117.919 853.749 241.801 1482.69 153.837C2111.63 65.8733 2585.54 148.706 2743.87 201.118"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 22.991C232.505 95.9276 853.749 219.81 1482.69 131.846C2111.63 43.8824 2585.54 126.715 2743.87 179.127"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 1C232.505 73.9367 853.749 197.819 1482.69 109.855C2111.63 21.8914 2585.54 104.724 2743.87 157.136"
                class="stroke-neutral-700/50" stroke="currentColor" />
        </svg>

        <div class="relative z-10">
            <div class="w-full max-w-5xl px-4 xl:px-0 py-10 lg:pt-16 mx-auto">
                <div class="inline-flex items-center">
                    <a class="flex-none rounded-md text-xl inline-block text-[#ff0] font-semibold focus:outline-hidden focus:opacity-80"
                        href="#" aria-label="Preline">
                        Vinify
                    </a>

                    <div class="border-s border-neutral-700 ps-5 ms-5">
                        <p class="text-sm text-neutral-400">
                            © 2025 Vinify Labs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/@loadingio/loading-bar@0.1.1/dist/loading-bar.min.js"></script>
    <div></div>
    <script>
        /* construct manually */
        var bar1 = new ldBar("#myItem1");
        /* ldBar stored in the element */
        var bar2 = document.getElementById('myItem1').ldBar;
        bar1.set(60);
    </script>
</x-app-layout>
