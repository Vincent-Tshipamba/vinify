<!-- Card -->
<div
    class="p-4 md:p-5 min-h-[200px] flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-sm text-gray-500 dark:text-neutral-500">
                Fichiers
            </h2>
            <p class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                {{ $nbrAnalyses }}
            </p>
        </div>

        <div>
            <span
                class="py-[5px] px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-md bg-teal-100 text-teal-800 dark:bg-teal-500/10 dark:text-teal-500">
                <svg class="inline-block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M12 5v14" />
                    <path d="m19 12-7 7-7-7" />
                </svg>
                25%
            </span>
        </div>
    </div>
    <!-- End Header -->

    <div data-preset="fan" class="ldBar label-center" id="myItem1" data-value="35"></div>
</div>
<!-- End Card -->
