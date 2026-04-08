@props(['name' => 'currency_code', 'selected' => 'USD', 'label' => 'Currency', 'required' => false])

<div x-data="currencyDropdown('{{ $selected }}')" class="relative">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    
    <!-- Selected Currency Display -->
    <button 
        type="button" 
        @click="open = !open" 
        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-left flex items-center justify-between focus:ring-2 focus:ring-primary-500 focus:border-transparent"
    >
        <span x-text="selectedLabel" class="truncate"></span>
        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" x-model="selected" {{ $required ? 'required' : '' }}>
    
    <!-- Dropdown -->
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl max-h-72 overflow-hidden"
    >
        <!-- Search Input -->
        <div class="p-3 border-b border-gray-100 sticky top-0 bg-white">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    x-model="search" 
                    x-ref="searchInput"
                    @focus="$refs.searchInput.select()"
                    placeholder="Search currency..." 
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
        </div>
        
        <!-- Currency List -->
        <div class="max-h-48 overflow-y-auto">
            <template x-for="currency in filteredCurrencies" :key="currency.code">
                <button 
                    type="button"
                    @click="selectCurrency(currency)"
                    class="w-full px-4 py-3 text-left hover:bg-primary-50 flex items-center gap-3 transition-colors"
                    :class="{'bg-primary-50 text-primary-700': selected === currency.code}"
                >
                    <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium" x-text="currency.symbol"></span>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm truncate" x-text="currency.code + ' - ' + currency.name"></p>
                    </div>
                    <svg x-show="selected === currency.code" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </template>
            
            <!-- No Results -->
            <div x-show="filteredCurrencies.length === 0" class="px-4 py-8 text-center text-gray-500">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">No currency found</p>
            </div>
        </div>
    </div>
</div>

<script>
function currencyDropdown(initialSelected) {
    return {
        open: false,
        search: '',
        selected: initialSelected,
        selectedLabel: '',
        currencies: @json(\App\Models\Currency::getActive()->map(fn($c) => ['code' => $c->code, 'name' => $c->name, 'symbol' => $c->symbol])),
        
        init() {
            this.updateLabel();
        },
        
        get filteredCurrencies() {
            if (!this.search) return this.currencies;
            const term = this.search.toLowerCase();
            return this.currencies.filter(c => 
                c.code.toLowerCase().includes(term) || 
                c.name.toLowerCase().includes(term) ||
                c.symbol.includes(term)
            );
        },
        
        selectCurrency(currency) {
            this.selected = currency.code;
            this.updateLabel();
            this.open = false;
            this.search = '';
        },
        
        updateLabel() {
            const currency = this.currencies.find(c => c.code === this.selected);
            this.selectedLabel = currency ? `${currency.symbol} ${currency.code} - ${currency.name}` : 'Select currency...';
        }
    }
}
</script>
