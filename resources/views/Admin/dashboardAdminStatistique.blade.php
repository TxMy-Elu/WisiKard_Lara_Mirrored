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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col">
    @include('menu.menuAdmin') <!-- Inclure le menu admin -->

    <div class="flex-1 md:ml-24 p-6 space-y-6">
        <!-- Bloc Sélection Année -->
        <div class="w-full max-w-md mx-auto p-6 bg-white rounded-xl border border-gray-300 shadow-xl flex flex-col items-center">
            <div class="mb-6 w-full text-center">
                <label for="yearSelect" class="block text-2xl font-extrabold text-gray-700">Sélectionnez l'année</label>
            </div>
            <form id="yearForm" action="{{ route('dashboardAdminStatistique') }}" method="get" class="w-full">
                <select name="year" id="yearSelect"
                        class="w-full py-2 px-4 text-center border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
            <!-- Compteur de vues total -->
            <div class="p-6 bg-white rounded-xl border border-gray-300 shadow-xl flex flex-col items-center">
                <div class="mb-4 text-center">
                    <p class="text-xl font-bold text-gray-700">Nombre de vues</p>
                    <p class="text-md text-gray-500">Global</p>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <h1 class="text-5xl font-extrabold text-red-900">{{ $totalViews }}</h1>
                </div>
            </div>

            <!-- Compteur des entreprises -->
            <div class="p-6 bg-white rounded-xl border border-gray-300 shadow-xl flex flex-col items-center">
                <div class="mb-4 text-center">
                    <p class="text-xl font-bold text-gray-700">Nombre d'Entreprises</p>
                    <p class="text-md text-gray-500">Global</p>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <h1 class="text-5xl font-extrabold text-red-900">{{ $totalEntreprise }}</h1>
                </div>
            </div>
        </div>

        <!-- Séparateur -->
        <div class="w-full my-6 border-t border-gray-300"></div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Graphique Annuel -->
            <div class="p-6 bg-white rounded-xl border border-gray-300 shadow-xl flex flex-col items-center">
                <canvas id="yearChart" class="w-full h-full max-h-96"></canvas>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const yearlyData = @json($yearlyData);
                        const ctxYear = document.getElementById('yearChart').getContext('2d');

                        // Graphique annuel
                        new Chart(ctxYear, {
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

            <!-- Graphique nb compte advanced et starter -->
            <div class="p-6 bg-white rounded-xl border border-gray-300 shadow-xl flex flex-col items-center">
                <canvas id="nbCompteChart" class="w-full h-full max-h-96"></canvas>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const nbCompteData = @json($nbCompteData);
                        const ctxNbCompte = document.getElementById('nbCompteChart').getContext('2d');

                        // Graphique nb compte advanced et starter
                        new Chart(ctxNbCompte, {
                            type: 'bar',
                            data: nbCompteData,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>

            <!-- Graphique nb template -->
            <div class="p-6 bg-white rounded-xl border border-gray-300 shadow-xl flex flex-col items-center">
                <canvas id="nbTemplateChart" class="w-full h-full max-h-96"></canvas>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const nbTemplateData = @json($nbTemplateData);
                        const ctxNbTemplate = document.getElementById('nbTemplateChart').getContext('2d');

                        // Graphique nb template
                        new Chart(ctxNbTemplate, {
                            type: 'bar',
                            data: nbTemplateData,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
</body>
</html>
