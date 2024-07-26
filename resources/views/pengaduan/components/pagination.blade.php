<nav aria-label="Page navigation example"
    class="flex justify-center py-3 text-gray-900 bg-white dark:text-white dark:bg-gray-800">
    <ul class="inline-flex -space-x-px text-sm mx-auto">
        <li>
            <a href="{{ $pengaduan->previousPageUrl() }}"
                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg 
                   {{ $pengaduan->onFirstPage() ? 'text-gray-300 cursor-not-allowed pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700' : 'hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}"
                @if ($pengaduan->onFirstPage()) aria-disabled="true" tabindex="-1" @endif>
                < </a>
        </li>

        @for ($i = 1; $i <= $pengaduan->lastPage(); $i++)
            <li>
                <a href="{{ $pengaduan->url($i) }}"
                    class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 
                       {{ $pengaduan->currentPage() == $i ? 'bg-blue-500 text-white' : 'bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                    {{ $i }}
                </a>
            </li>
        @endfor

        <li>
            <a href="{{ $pengaduan->nextPageUrl() }}"
                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg 
                   {{ $pengaduan->hasMorePages() ? 'hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : 'text-gray-300 cursor-not-allowed pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700' }}"
                @if (!$pengaduan->hasMorePages()) aria-disabled="true" tabindex="-1" @endif>
                >
            </a>
        </li>
    </ul>
</nav>
