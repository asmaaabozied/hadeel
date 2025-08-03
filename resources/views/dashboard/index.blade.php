@extends('layouts.app')

@section('title', 'joGames')

@section('content')

    <!-- Start Content -->

    {{--    Statistics cards --}}
    <div class="relative mb-4 w-20">
        <select class="form-select font-semibold text-sm px-2 dark:bg-black dark:text-white">
            <option class="px-2" value="today">Today</option>
            <option class="px-2" value="week">Week</option>
            <option class="px-2" value="month">Month</option>
        </select>
    </div>
    <div class="flex flex-col gap-7">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-7">
            <div class="bg-lightblue-100 rounded-2xl p-6">
                <p class="text-sm font-semibold text-black mb-2">Views</p>
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl leading-9 font-semibold text-black">721K</h2>
                    <div class="flex items-center gap-1">
                        <p class="text-xs leading-[18px] text-black">+11.01%</p>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                                fill="#1C1C1C" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-lightpurple-100 rounded-2xl p-6">
                <p class="text-sm font-semibold text-black mb-2">Visits</p>
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl leading-9 font-semibold text-black">367K</h2>
                    <div class="flex items-center gap-1">
                        <p class="text-xs leading-[18px] text-black">+9.15%</p>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                                fill="#1C1C1C" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-lightblue-100 rounded-2xl p-6">
                <p class="text-sm font-semibold text-black mb-2">New Users</p>
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl leading-9 font-semibold text-black">1,156</h2>
                    <div class="flex items-center gap-1">
                        <p class="text-xs leading-[18px] text-black">-0.56%</p>
                        <svg width="16" height="16" class="rotate-180" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                                fill="#1C1C1C" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-lightpurple-100 rounded-2xl p-6">
                <p class="text-sm font-semibold text-black mb-2">Active Users</p>
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl leading-9 font-semibold text-black">239K</h2>
                    <div class="flex items-center gap-1">
                        <p class="text-xs leading-[18px] text-black">-1.48%</p>
                        <svg width="16" height="16" class="rotate-180" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                                fill="#1C1C1C" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{--    Statistics cards --}}

    {{--    start charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-16">
        <!-- Line Chart: Total Users -->
        <div class="bg-[#f7f9fb] p-6 rounded-2xl shadow">
            <div class="flex justify-between items-center mb-4">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">Total Users</div>
                <div class="flex space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold text-black dark:text-white">● Current Week</span>
                    <span>○ Previous Week</span>
                </div>
            </div>
            <div id="lineChart" class="h-64"></div>
        </div>

        <!-- Traffic by Website -->
        <div class="bg-[#f7f9fb]  p-6 rounded-2xl shadow">
            <div class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Traffic by Website</div>
            <div id="horizontalBarChart" class="h-64"></div>
        </div>

        <!-- Traffic by Device -->
        <div class="bg-[#f7f9fb]  p-6 rounded-2xl shadow">
            <div class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Traffic by Device</div>
            <div id="barChart" class="h-64"></div>
        </div>

        <!-- Traffic by Location -->
        <div class="bg-[#f7f9fb]  p-6 rounded-2xl shadow">
            <div class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Traffic by Location</div>
            <div id="pieChart" class="h-64"></div>
        </div>
    </div>

    {{--    end charts --}}

    <!-- End Content -->

@endsection

<!-- Scripts for ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("alpine:init", () => {
        // Line chart
        new ApexCharts(document.querySelector("#lineChart"), {
            chart: {
                type: 'line',
                height: 250,
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth',
            },
            series: [{
                    name: 'Current Week',
                    data: [1, 2, 3, 6, 5, 8, 10]
                },
                {
                    name: 'Previous Week',
                    data: [1.2, 2.5, 4, 5, 6.5, 7.5, 9.2]
                }
            ],
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            },
            colors: ['#1C1C1C', '#60A5FA']
        }).render();

        // Bar chart
        new ApexCharts(document.querySelector("#barChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Devices',
                data: [100, 650, 400, 500, 600, 800]
            }],
            xaxis: {
                categories: ['Linux', 'Mac', 'iOS', 'Windows', 'Android', 'Other']
            },
            colors: ['#8B5CF6']
        }).render();

        // Pie chart
        new ApexCharts(document.querySelector("#pieChart"), {
            chart: {
                type: 'donut',
                height: 250
            },
            series: [38.5, 22.5, 30.8, 8.1],
            labels: ['United States', 'Canada', 'Mexico', 'Other'],
            colors: ['#A7F3D0', '#C4B5FD', '#000000', '#818CF8']
        }).render();

        // Horizontal bar
        new ApexCharts(document.querySelector("#horizontalBarChart"), {
            chart: {
                type: 'bar',
                height: 250,
                stacked: true
            },
            plotOptions: {
                bar: {
                    horizontal: true
                }
            },
            series: [{
                data: [40, 35, 25, 20, 15, 10, 5]
            }],
            xaxis: {
                categories: ['Google', 'Youtube', 'Instagram', 'Pinterest', 'Facebook', 'Twitter',
                    'Tumblr'
                ]
            },
            colors: ['#60A5FA']
        }).render();
    });
</script>
