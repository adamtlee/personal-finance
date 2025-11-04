<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Instructions --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header-actions-item border-gray-200 bg-white px-4 py-5 dark:border-white/10 dark:bg-gray-900 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Search for Stock Information
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Use the "Search Stock" button in the header to search for stock information from BigData.com.
                    Enter a stock ticker symbol (e.g., ISPO, AAPL, MSFT) to retrieve company data.
                </p>
            </div>
        </div>

        {{-- Search Results --}}
        @if($this->hasSearched && $this->searchResults)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="border-b border-gray-200 bg-white px-4 py-5 dark:border-white/10 dark:bg-gray-900 sm:px-6">
                    <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Search Results for: <span class="font-bold text-primary-600 dark:text-primary-400">{{ $this->ticker }}</span>
                    </h3>
                </div>
                
                <div class="px-4 py-5 sm:px-6 space-y-4">
                    @foreach($this->searchResults as $key => $value)
                        @if($this->isNested($value))
                            {{-- Nested Object/Array Card --}}
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-gray-800">
                                <h4 class="mb-3 text-sm font-semibold text-gray-950 capitalize dark:text-white">
                                    {{ str_replace('_', ' ', $key) }}
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($value as $nestedKey => $nestedValue)
                                        <div class="space-y-1">
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 capitalize">
                                                {{ str_replace('_', ' ', $nestedKey) }}
                                            </div>
                                            @if($this->isNested($nestedValue) || $this->isList($nestedValue))
                                                <div class="text-xs bg-white dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-white/10 overflow-x-auto">
                                                    <pre class="whitespace-pre-wrap text-xs">{{ $this->formatValue($nestedValue) }}</pre>
                                                </div>
                                            @else
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $this->formatValue($nestedValue) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($this->isList($value))
                            {{-- List/Array Card --}}
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-gray-800">
                                <h4 class="mb-3 text-sm font-semibold text-gray-950 capitalize dark:text-white">
                                    {{ str_replace('_', ' ', $key) }}
                                </h4>
                                
                                <div class="space-y-2">
                                    @foreach($value as $item)
                                        <div class="bg-white dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-white/10">
                                            @if(is_array($item))
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach($item as $itemKey => $itemValue)
                                                        <div>
                                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 capitalize">
                                                                {{ str_replace('_', ' ', $itemKey) }}:
                                                            </span>
                                                            <span class="text-sm text-gray-900 dark:text-gray-100 ml-1">
                                                                {{ $this->formatValue($itemValue) }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $this->formatValue($item) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Simple Key-Value Pair --}}
                            <div class="flex items-start justify-between py-2 border-b border-gray-200 dark:border-white/10 last:border-0">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize">
                                    {{ str_replace('_', ' ', $key) }}
                                </div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 text-right ml-4">
                                    {{ $this->formatValue($value) }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @elseif($this->hasSearched && $this->searchResults === null)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="px-4 py-8 text-center sm:px-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No results found for "{{ $this->ticker }}".</p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

