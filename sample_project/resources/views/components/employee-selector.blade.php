<div class="relative" x-data="employeeSelector()" x-init="init()">
    <input type="hidden" name="{{ $name }}" x-model="selectedId" @if($required) required @endif>
    
    <div class="relative">
        <input 
            type="text" 
            id="{{ $id }}_search"
            x-model="searchQuery"
            @input="filterEmployees()"
            @focus="open = true; filterEmployees()"
            @click.away="closeWithDelay()"
            @keydown.escape="open = false"
            @keydown.enter.prevent="selectFirst()"
            placeholder="{{ $placeholder }}"
            class="block w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
            :class="{ 'pl-10': selectedId }"
        >
        
        <div x-show="selectedId" class="absolute left-3 top-1/2 -translate-y-1/2">
            <span class="h-5 w-5 rounded-full bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center text-xs font-bold" x-text="selectedAvatar"></span>
        </div>
        
        <div x-show="selectedId" @click="clearSelection()" class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
    </div>
    
    <div 
        x-show="open && filteredEmployees.length > 0"
        x-transition
        class="absolute z-50 mt-1 w-full max-h-56 overflow-auto rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-lg"
    >
        <template x-for="emp in filteredEmployees" :key="emp.id">
            <button 
                type="button"
                @click="selectEmployee(emp)"
                class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 flex items-center gap-2"
                :class="{ 'bg-blue-50 dark:bg-blue-900/20': selectedId === emp.id }"
            >
                <span class="h-8 w-8 rounded-full bg-gray-100 dark:bg-neutral-800 text-gray-600 dark:text-gray-400 flex items-center justify-center text-sm font-bold" x-text="emp.avatar"></span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="emp.name"></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="emp.position + ' | ' + emp.division"></div>
                </div>
                <span x-show="selectedId === emp.id" class="text-[#013CFC]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </button>
        </template>
    </div>
    
    <div x-show="open && searchQuery && filteredEmployees.length === 0" class="absolute z-50 mt-1 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-lg px-3 py-2 text-sm text-gray-500">
        No employees found
    </div>
</div>

<script>
function employeeSelector() {
    return {
        open: false,
        searchQuery: '{{ $selectedValue ? ($employees[array_search($selectedValue, array_column($employees, 'id'))]['name'] ?? '') : '' }}',
        selectedId: '{{ $selectedValue ?? '' }}',
        selectedAvatar: '{{ $selectedValue ? ($employees[array_search($selectedValue, array_column($employees, 'id'))]['avatar'] ?? '') : '' }}',
        employees: @json($employees),
        filteredEmployees: [],
        
        init() {
            this.filteredEmployees = this.employees.slice(0, 50);
        },
        
        filterEmployees() {
            const query = this.searchQuery.toLowerCase();
            this.filteredEmployees = this.employees.filter(emp => 
                emp.name.toLowerCase().includes(query) || 
                emp.id.toLowerCase().includes(query) ||
                emp.position.toLowerCase().includes(query)
            ).slice(0, 50);
        },
        
        selectEmployee(emp) {
            this.selectedId = emp.id;
            this.selectedAvatar = emp.avatar;
            this.searchQuery = emp.name + ' (' + emp.id + ')';
            this.open = false;
        },
        
        selectFirst() {
            if (this.filteredEmployees.length > 0) {
                this.selectEmployee(this.filteredEmployees[0]);
            }
        },
        
        clearSelection() {
            this.selectedId = '';
            this.selectedAvatar = '';
            this.searchQuery = '';
            this.open = true;
            this.filterEmployees();
        },
        
        closeWithDelay() {
            setTimeout(() => { this.open = false; }, 150);
        }
    }
}
</script>
