<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <!-- Inclure les bibliothèques Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
            integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col">
    @include('menu.menuAdmin') <!-- Inclure le menu admin -->

    <div class="flex-1 md:ml-24 p-6">
        <!-- Bloc Sélection Année -->
        <div class="w-full md:w-1/3 mx-auto p-6 bg-white rounded-lg border border-gray-300 shadow-md mb-6 flex flex-col justify-between items-center">
            <div class="mb-4 w-full text-center">
                <label for="yearSelect" class="block text-2xl font-bold text-gray-700">Sélectionnez l'année</label>
            </div>
            <form id="yearForm" action="{{ route('dashboardAdminStatistique') }}" method="get"
                  class="flex items-center w-full justify-center">
                <select name="year" id="yearSelect" class="w-32 text-center border border-gray-300 rounded-md px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Statistiques et Graphique -->
        <div class="flex flex-wrap justify-center gap-6">
            <!-- Compteur de vues total -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border border-gray-300 shadow-md flex flex-col">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombre de vues</p>
                    <p class="text-center text-xl">Global</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalViews }}</h1>
                </div>
            </div>

            <!-- Compteur des entreprises -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border border-gray-300 shadow-md flex flex-col">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombre d'Entreprises</p>
                    <p class="text-center text-xl">Global</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalEntreprise }}</h1>
                </div>
            </div>

            <!-- Graphique Annuel -->
            <div class="w-full md:w-2/3 p-6 bg-white rounded-lg border border-gray-300 shadow-md flex justify-center items-center">
                <canvas id="yearChart" width="100" height="50"></canvas>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const yearlyData = @json($yearlyData);
                        const ctxYear = document.getElementById('yearChart').getContext('2d');

                        // Graphique annuel
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
