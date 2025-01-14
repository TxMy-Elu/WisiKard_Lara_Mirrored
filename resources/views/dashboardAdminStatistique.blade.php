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
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col">
    @include('menuAdmin')

    <div class="flex-1 md:ml-24 p-6">
        <div class="w-full md:w-1/3 mx-auto p-6 bg-white rounded-lg border shadow-md mb-6 flex flex-col justify-between items-center">
            <div class="mb-4 w-full text-center">
                <label for="yearSelect" class="block text-2xl font-bold text-gray-700">Select Year</label>
            </div>
            <form id="yearForm" action="{{ route('dashboardAdminStatistique') }}" method="get" class="flex items-center w-full justify-center">
                <select name="year" id="yearSelect" class="custom-select w-32 text-center">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex flex-wrap justify-center gap-6">
            <!-- Compteur de nombre de vues total -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border shadow-md flex flex-col">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombre de vues</p>
                    <p class="text-center text-xl">Global</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalViews }}</h1>
                </div>
            </div>

            <!-- Compteur de nombre d'entreprises -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border shadow-md flex flex-col">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombre d'Entreprises</p>
                    <p class="text-center text-xl">Global</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalEntreprise }}</h1>
                </div>
            </div>

            <!-- Graph -->
            <div class="w-full md:w-2/3 p-6 bg-white rounded-lg border shadow-md flex justify-center items-center">
                <canvas id="yearChart" width="100" height="50"></canvas>
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
    </div>
</div>

</body>
</html>