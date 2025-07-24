<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl sm:text-4xl font-light text-gray-800 dark:text-gray-200 mb-2 transition-all duration-300">
            Analyses
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
            Here you can view your recent analyses.
        </p>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4">
            @if($analyses->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No analyses found.</p>
            @else
                <ul class="space-y-4">
                    @foreach($analyses as $analysis)
                        <li class="border-b border-gray-200 dark:border-neutral-700 pb-2">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $analysis->title }}</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $analysis->created_at->format('d M Y, H:i') }}</p>
                            <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $analysis->description }}</p>
                        </li>
                    @endforeach
                </ul>
                {{ $analyses->links() }}
            @endif
        </div>
    </div>
</x-app-layout>