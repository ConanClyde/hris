@props([
    'id',
    'type' => 'line',
    'title' => '',
    'labels' => [],
    'datasets' => [],
    'colors' => ['#013CFC'],
    'height' => 250,
    'options' => [],
])

<div id="{{ $id }}" class="frappe-chart-wrapper"></div>

@push('scripts')
<script>
(function () {
    var cfg = {
        type: '{{ $type }}',
        height: {{ $height }},
        colors: @json($colors),
        animate: 1,
        data: {
            labels: @json($labels),
            datasets: @json($datasets)
        },
        @if($title)
        title: '{{ $title }}',
        @endif
        axisOptions: {
            xAxisMode: 'tick',
            xIsSeries: true,
            shortenYAxisNumbers: true,
        },
        lineOptions: {
            hideDots: 1,
            regionFill: 1,
            spline: 0,
            dotSize: 3,
            heatline: 0,
        },
        barOptions: {
            spaceRatio: 0.5,
            stacked: 0,
        },
        tooltipOptions: {
            formatTooltipX: function (d) { return d; },
            formatTooltipY: function (d) {
                return typeof d === 'number' ? d.toLocaleString() : d;
            },
        },
        truncateLegends: true,
        maxSlices: 10,
        isNavigable: false,
    };

    var extra = @json($options);
    for (var key in extra) {
        if (extra.hasOwnProperty(key)) {
            if (typeof cfg[key] === 'object' && typeof extra[key] === 'object' && !Array.isArray(extra[key])) {
                Object.assign(cfg[key], extra[key]);
            } else {
                cfg[key] = extra[key];
            }
        }
    }

    var id = '{{ $id }}';
    var run = function() {
        if (window.frappe && window.frappe.Chart) {
            new frappe.Chart('#' + id, cfg);
        } else {
            (window.__frappeChartQueue = window.__frappeChartQueue || []).push(run);
        }
    };
    run();
})();
</script>
@endpush
