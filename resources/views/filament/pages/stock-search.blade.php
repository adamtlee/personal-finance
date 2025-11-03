<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Instructions --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Search for Stock Information</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Use the "Search Stock" button in the header to search for stock information from BigData.com.
                Enter a stock ticker symbol (e.g., ISPO, AAPL, MSFT) to retrieve company data.
            </p>
        </div>

        {{-- Search Results --}}
        @if($this->hasSearched && $this->searchResults)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Search Results for: {{ $this->ticker }}</h2>
                
                <div class="space-y-4">
                    <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded overflow-x-auto text-sm">{{ json_encode($this->searchResults, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        @elseif($this->hasSearched && $this->searchResults === null)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No results found for "{{ $this->ticker }}".</p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

