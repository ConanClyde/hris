<?php

namespace App\View\Components;

use App\Features\Employees\Models\Employee;
use Illuminate\View\Component;

class EmployeeSelector extends Component
{
    public string $name;

    public string $id;

    public ?string $selectedValue;

    public string $placeholder;

    public bool $required;

    public array $employees;

    public function __construct(
        string $name = 'employee_id',
        ?string $id = null,
        ?string $selectedValue = null,
        string $placeholder = 'Search employee...',
        bool $required = true
    ) {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->selectedValue = $selectedValue;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->employees = $this->loadEmployees();
    }

    private function loadEmployees(): array
    {
        return Employee::where('status', 'active')
            ->select('id', 'first_name', 'last_name', 'middle_name', 'position', 'division')
            ->orderBy('last_name')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'name' => $e->full_name,
                'position' => $e->position,
                'division' => $e->division,
                'avatar' => strtoupper(substr($e->first_name, 0, 1)),
            ])
            ->toArray();
    }

    public function render()
    {
        return view('components.employee-selector');
    }
}
