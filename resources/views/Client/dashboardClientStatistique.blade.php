<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
            integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col">
    @include('menu.menuClient')

    <div class="flex-1 md:ml-24 p-6 grid grid-cols-4 grid-rows-5 gap-5">
        <!-- divStat1 -->
        <div class="col-span-2 row-span-1 bg-white rounded-lg border shadow-md p-6 flex items-center justify-center">
            <form id="yearWeekForm" action="{{ route('dashboardClientStatistique') }}" method="get"
                  class="flex items-center justify-center w-full">
                <!-- Conteneur des champs année et semaine sur la même ligne avec ESPACE -->
                <div class="flex items-center space-x-52">
                    <!-- Bloc de sélection de l'année -->
                    <div class="text-center">
                        <label for="yearSelect" class="block text-2xl font-bold text-gray-800">Sélectionnez
                            l'année</label>
                        <select name="year" id="yearSelect"
                                class="w-32 text-center border border-gray-300 rounded-md p-2 text-lg focus:ring focus:ring-indigo-500 focus:outline-none"
                                onchange="updateWeekToCurrent()">
                            @foreach($years as $year)
                                <option value="{{ $year }}"
                                        @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bloc de sélection de la semaine -->
                    <div class="text-center">
                        <label for="weekSelect" class="block text-2xl font-bold text-gray-800">Sélectionnez la
                            semaine</label>
                        <div class="flex items-center justify-center">
                            <input type="hidden" name="week" id="weekInput" value="{{ $selectedWeek }}">
                            <button type="button" onclick="changeWeek(-1)"
                                    class="bg-transparent hover:bg-gray-400 text-red-600 font-bold py-2 px-4 rounded-l">
                                &lt;
                            </button>
                            <span id="weekDisplay" class="bg-transparent text-red-600 font-bold py-2 px-4">
                                {{ $selectedWeek ? $selectedWeek : date('W') }}
                            </span>
                            <button type="button" onclick="changeWeek(1)"
                                    class="bg-transparent hover:bg-gray-400 text-red-600 font-bold py-2 px-4 rounded-r">
                                &gt;
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- divStat2 (Compteur global) -->
        <div class="col-start-4 row-span-1 bg-white rounded-lg border shadow-md p-6">
            <div class="text-center mb-4">
                <p class="font-bold text-2xl text-gray-800">Nombre de vues</p>
                <p class="text-xl text-gray-800">Global</p>
            </div>
            <div class="flex items-center justify-center">
                <h1 class="text-7xl font-bold text-red-900">{{ $totalViewsCard }}</h1>
            </div>
        </div>

        <!-- divStat3 (Compteur hebdomadaire) -->
        <div class="col-start-4 row-start-2 bg-white rounded-lg border shadow-md p-6">
            <div class="text-center mb-4">
                <p class="font-bold text-2xl text-gray-800">Nombre de vues</p>
                <p class="text-xl text-gray-800">Semaine</p>
            </div>
            <div class="flex items-center justify-center">
                @if($selectedWeek)
                    <h1 class="text-7xl font-bold text-red-900">{{ $weeklyViews[$selectedWeek] ?? 0 }}</h1>
                @else
                    @foreach($weeklyViews as $week => $count)
                        <p>Semaine {{ $week }} : {{ $count }} vues</p>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- div place vide -->
        <div class="col-span-2 row-span-1"></div>

        <!-- divStat4 (Graphique par employés) -->
        <div class="relative col-span-2 row-span-3 bg-white rounded-lg border shadow-md p-6">
            @if($compte->role == 'starter')
                <!-- Message abonnement, centré au-dessus du blur -->
                <div class="relative z-50 flex flex-col items-center justify-center mb-10">
                    <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/"
                       target="_blank"
                       class="bg-red-500 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-xl w-48 h-7 flex items-center justify-center space-x-4">
                        <p class="text-white text-base text-gray-800">Mettre à niveau</p>
                        <!-- svg cursor mouse -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                             viewBox="0 0 24 24"
                             fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-mouse-pointer">
                            <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                            <path d="M13 13l6 6"></path>
                        </svg>
                    </a>
                </div>
            @endif

            <div class="@if($compte->role == 'starter') blur-sm pointer-events-none opacity-50 @endif  flex flex-col items-center justify-center ">
                <div class="text-center mb-4">
                    <p class="font-bold text-2xl text-gray-800">Nombres de vues</p>
                    <p class="text-xl  text-gray-800">Par employes</p>
                </div>
                @if(empty($employerData['datasets'][0]['data']))
                    <p class="text-center text-lg text-gray-500">Aucune donnée disponible pour le graphique.</p>
                @else
                    <!-- Réduction de la taille du graphique avec des classes pour ajuster largeur et hauteur -->
                    <div class="w-full flex justify-center overflow-hidden">
                        <canvas id="yearChart" class="max-w-xs max-h-96"></canvas>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const employerData = @json($employerData);
                            const ctxYear = document.getElementById('yearChart').getContext('2d');

                            new Chart(ctxYear, {
                                type: 'pie',
                                data: employerData,
                                options: {
                                    responsive: true,
                                }
                            });
                        });
                    </script>
                @endif
            </div>
        </div>

        <!-- divStat5 (Graphique par mois) -->
        <div class="col-span-2 row-span-3 bg-white rounded-lg border shadow-md p-6 flex flex-col items-center justify-center">
            <div class="text-center mb-4">
                <p class="font-bold text-2xl text-gray-800">Nombres de vues</p>
                <p class="text-xl text-gray-800">Par mois</p>
            </div>
            @if(empty($monthlyData['datasets'][0]['data']))
                <p class="text-center text-lg text-gray-500">Aucune donnée disponible pour le graphique.</p>
            @else
                <div class="w-full overflow-hidden">
                    <canvas id="monthChart"></canvas>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const monthlyData = @json($monthlyData);
                        const ctxMonth = document.getElementById('monthChart').getContext('2d');

                        new Chart(ctxMonth, {
                            type: 'bar',
                            data: monthlyData,
                            options: {
                                responsive: true,
                                scales: {
                                    x: {
                                        display: true
                                    },
                                    y: {
                                        display: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endif
        </div>
    </div>
</div>

<script>
    function changeWeek(direction) {
        let weekInput = document.getElementById('weekInput');
        let weekDisplay = document.getElementById('weekDisplay');
        let currentWeek = parseInt(weekInput.value) || new Date().getWeek(); // Récupération correcte de la semaine
        let newWeek = currentWeek + direction;

        // Bloquer entre semaine 1 et semaine 52
        if (newWeek >= 1 && newWeek <= 52) {
            weekInput.value = newWeek; // Pas besoin d'ajouter une unité supplémentaire
            weekDisplay.innerText = newWeek;
            document.getElementById('yearWeekForm').submit();
        }
    }

    function updateWeekToCurrent() {
        let currentWeek = new Date().getWeek();
        document.getElementById('weekInput').value = currentWeek;
        document.getElementById('weekDisplay').innerText = currentWeek;
        document.getElementById('yearWeekForm').submit();
    }

    // Extension pour obtenir le numéro de semaine
    Date.prototype.getWeek = function () {
        const firstDayOfYear = new Date(this.getFullYear(), 0, 1);
        const pastDaysOfYear = (this - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    };
</script>

</body>
</html>