<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
            integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">
    @include('menuAdmin')

    <div class="mx-auto p-6">
        <!-- compteur de nombre de vue total -->
        <div class="flex justify-center">
            <div class="w-96 h-60 p-4 bg-white rounded-lg border flex flex-col ">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl ">Nombre de vues</p>
                    <p class="text-center text-xl">Global</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalViews }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto p-6">
        <!-- compteur de nombre de vue total -->
        <div class="flex justify-center">
            <div class="w-96 h-60 p-4 bg-white rounded-lg border flex flex-col ">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl ">Nombre d'Entreprises</p>
                    <p class="text-center text-xl">Global</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalEntreprise }}</h1>
                </div>
            </div>
        </div>
    </div>


    <div class="mx-auto p-6">
        <div class="mb-4">
            <label for="yearSelect" class="block text-sm font-medium text-gray-700">Select Year</label>
            <form id="yearForm" action="{{ route('dashboardAdminStatistique') }}" method="get">
                <select name="year" id="yearSelect"
                        class="custom-width p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($years as $year)
                        <option value="{{ $year }}"
                                @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
                <label for="monthSelect" class="block text-sm font-medium text-gray-700 mt-4">Select
                    Month</label>
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

                document.getElementById('yearSelect').addEventListener('change', function () {
                    document.getElementById('yearForm').submit();
                });
            });
        </script>
    </div>

</div>

</body>
</html>
