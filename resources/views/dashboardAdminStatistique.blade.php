<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        .custom-width {
            width: 100%; /* Adjusted to be responsive */
        }

        .search-icon {
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .box-shadow {
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 1);
        }

        @media (max-width: 768px) {
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 1000;
            }

            .content {
                margin-top: 60px; /* Adjust based on the height of the navbar */
            }
        }
    </style>
</head>
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">
    @include('menuAdmin')

    <div class="mx-auto p-6">
        <div class="mb-4">
            <label for="yearSelect" class="block text-sm font-medium text-gray-700">Select Year</label>
            <form id="yearForm" action="{{ route('dashboardAdminStatistique') }}" method="get">
                <select name="year" id="yearSelect"
                        class="custom-width p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
                <label for="monthSelect" class="block text-sm font-medium text-gray-700 mt-4">Select Month</label>
                <select name="month" id="monthSelect"
                        class="custom-width p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Months</option>
                    @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $key => $monthName)
                        <option value="{{ $key }}" @if($key == $month) selected @endif>{{ $monthName }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <canvas id="yearChart" width="400" height="200"></canvas>
        @if($month)
            <canvas id="weekChart" width="400" height="200" class="mt-6"></canvas>
        @endif
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const yearlyData = @json($yearlyData);
                const ctxYear = document.getElementById('yearChart').getContext('2d');

                // Yearly chart
                let yearChart = new Chart(ctxYear, {
                    type: 'line',
                    data: yearlyData,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                @if($month)
                const weeklyData = @json($weeklyData);
                const ctxWeek = document.getElementById('weekChart').getContext('2d');

                // Weekly chart
                let weekChart = new Chart(ctxWeek, {
                    type: 'line',
                    data: weeklyData,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                @endif

                document.getElementById('yearSelect').addEventListener('change', function () {
                    document.getElementById('yearForm').submit();
                });

                document.getElementById('monthSelect').addEventListener('change', function () {
                    document.getElementById('yearForm').submit();
                });
            });
        </script>
    </div>

</div>
</body>
</html>