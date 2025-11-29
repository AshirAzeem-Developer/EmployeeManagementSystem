<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Message --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">Here is your attendance summary for this month.</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-base font-medium text-gray-700">Monthly Attendance Overview</h4>
                        
                        {{-- Legend --}}
                        <div class="flex flex-wrap gap-3 text-xs">
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span> Present</div>
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-yellow-500 mr-1"></span> Late</div>
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-1"></span> Absent</div>
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-teal-500 mr-1"></span> Half Day</div>
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-purple-500 mr-1"></span> Leave</div>
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-gray-500 mr-1"></span> Off/Holiday</div>
                        </div>
                    </div>
                    <div id="attendanceChart"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ApexCharts Script --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: 'Working Hours',
                    data: @json($hours)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        colors: {
                            ranges: [
                                @foreach($colors as $index => $color)
                                {
                                    from: {{ $index }},
                                    to: {{ $index }},
                                    color: '{{ $color }}'
                                },
                                @endforeach
                            ]
                        },
                        columnWidth: '60%',
                        borderRadius: 4,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($dates),
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    title: {
                        text: 'Hours',
                        style: {
                            color: '#6b7280'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#6b7280'
                        }
                    }
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " hours"
                        }
                    },
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        var status = @json($statuses)[dataPointIndex];
                        var hours = series[seriesIndex][dataPointIndex];
                        var color = w.config.plotOptions.bar.colors.ranges[dataPointIndex].color;
                        
                        return '<div class="px-3 py-2 text-sm font-medium text-white rounded shadow-lg" style="background-color:' + color + '">' +
                            '<span>' + status + '</span>' +
                            (hours > 0 ? '<div class="text-xs opacity-90 mt-1">Hours: ' + hours + '</div>' : '') +
                            '</div>';
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>
