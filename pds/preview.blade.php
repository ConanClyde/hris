<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDS Preview</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 text-ink">
    <x-pds-print-styles />
    {{-- Floating Print Button - hidden during print --}}
    <div class="print-controls fixed top-4 right-4 z-50 flex gap-2 print:hidden" id="pds-controls">
        <a href="javascript:history.back()" id="pds-back-btn"
            class="px-4 py-2 bg-white border border-border text-ink rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 transition-colors inline-flex items-center gap-2">
            <x-icons.arrow-left class="w-4 h-4" />
            Back
        </a>
        <button onclick="window.print()"
            class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium shadow-sm hover:bg-primary-light transition-colors cursor-pointer inline-flex items-center gap-2">
            <x-icons.printer class="w-4 h-4" />
            Print / Save as PDF
        </button>
    </div>
    <script>
        if (window.self !== window.top) {
            // Hide Back button if in iframe
            document.getElementById('pds-back-btn').style.display = 'none';
        }
    </script>

    <div class="min-h-screen p-0 sm:p-6 space-y-8 print:space-y-0 print:p-0">
        {{-- Page 1 --}}
        <div class="pds-page">
            @include('employee.pds.partials.print_c1', [
                'pds' => $pds ?? null,
                'family' => $family ?? null,
                'children' => $children ?? collect([]),
            ])
        </div>

        {{-- Page 2 --}}
        <div class="pds-page">
            @include('employee.pds.partials.print_c2', [
                'education' => $education ?? collect([]),
                'csc_eligibility' => $csc_eligibility ?? collect([]),
            ])
        </div>

        {{-- Page 3 --}}
        <div class="pds-page">
            @include('employee.pds.partials.print_c3', [
                'work_experience' => $work_experience ?? collect([]),
                'voluntary_work' => $voluntary_work ?? collect([]),
                'training_records' => $training_records ?? collect([]),
                'other_info' => $other_info ?? collect([]),
            ])
        </div>

        {{-- Page 4 --}}
        <div class="pds-page">
            @include('employee.pds.partials.print_c4', [
                'check' => $check ?? null,
                'reference_records' => $reference_records ?? collect([]),
                'govid_records' => $govid_records ?? collect([]),
            ])
        </div>
    </div>
</body>
</html>

