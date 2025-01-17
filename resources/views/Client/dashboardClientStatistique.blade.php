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
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col">
    @include('menu.menuClient')

    <div class="flex-1 md:ml-24 p-6">
        <div class="w-full md:w-1/3 mx-auto p-6 bg-white rounded-lg border shadow-md mb-6 flex flex-col justify-between items-center">
            <form id="yearMonthWeekForm" action="{{ route('dashboardClientStatistique') }}" method="get"
                  class="flex flex-col items-center w-full">
                <div class="mb-4 w-full text-center">
                    <label for="yearSelect" class="block text-2xl font-bold text-gray-700">Sélectionnez l'année</label>
                    <select name="year" id="yearSelect" class="custom-select w-32 text-center mb-4">
                        @foreach($years as $year)
                            <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-full text-center">
                    <label for="monthSelect" class="block text-2xl font-bold text-gray-700">Sélectionnez le mois</label>
                    <select name="month" id="monthSelect" class="custom-select w-32 text-center mb-4">
                        @foreach($months as $month)
                            <option value="{{ $month }}" @if($month == $selectedMonth) selected @endif>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-full text-center">
                    <label for="weekSelect" class="block text-2xl font-bold text-gray-700">Sélectionnez la
                        semaine</label>
                </div>
                <div class="flex items-center w-full justify-center">
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
                <div class="mt-4">
                    <button type="button" onclick="updateStatistics()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Mettre à jour les statistiques
                    </button>
                </div>
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
                    <h1 class="text-7xl font-bold text-red-900" id="totalViewsCard">{{ $totalViewsCard }}</h1>
                </div>
            </div>

            <!-- Compteur de nombre de vues total mois -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border shadow-md flex flex-col">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombre de vues</p>
                    <p class="text-center text-xl">Mois</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900" id="monthlyViewsCount">{{ $monthlyViews[$selectedMonth] ?? 0 }}</h1>
                </div>
            </div>

            <!-- Compteur de nombre de vues total semaine -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border shadow-md flex flex-col">
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombre de vues</p>
                    <p class="text-center text-xl">Semaine</p>
                </div>
                <div class="flex flex-grow justify-center items-center">
                    <h1 class="text-7xl font-bold text-red-900" id="weeklyViewsCount">{{ $weeklyViews[$selectedWeek] ?? 0 }}</h1>
                </div>
            </div>

            <!-- Graph -->
            <div class="w-full md:w-1/3 p-6 bg-white rounded-lg border shadow-md flex flex-col justify-center items-center mx-auto">
                <!-- titre du graph-->
                <div class="mb-4">
                    <p class="text-center font-bold text-2xl">Nombres de vues</p>
                    <p class="text-center text-xl">Par employés</p>
                </div>

                @if(empty($employerData['datasets'][0]['data']))
                    <p>Aucune donnée disponible pour le graphique.</p>
                @else
                    <canvas id="yearChart" width="100" height="50"></canvas>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const employerData = @json($employerData);
                            const ctxYear = document.getElementById('yearChart').getContext('2d');

                            // employer chart
                            let employe = new Chart(ctxYear, {
                                type: 'pie',
                                data: employerData,
                                options: {
                                    scales: {
                                        x: {
                                            display: false
                                        },
                                        y: {
                                            display: false
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
</div>

</body>

<script>
    function changeWeek(direction) {
        let weekInput = document.getElementById('weekInput');
        let weekDisplay = document.getElementById('weekDisplay');
        let currentWeek = parseInt(weekInput.value) - 1 || new Date().getWeek() - 1;
        let newWeek = currentWeek + direction;

        if (newWeek >= 0 && newWeek < 52) {
            weekInput.value = newWeek + 1;
            weekDisplay.innerText = newWeek + 1;
            updateStatistics();
        }
    }

    function updateWeekToCurrent() {
        let weekInput = document.getElementById('weekInput');
        let weekDisplay = document.getElementById('weekDisplay');
        let currentWeek = new Date().getWeek() - 1;

        weekInput.value = currentWeek + 1;
        weekDisplay.innerText = currentWeek + 1;
        updateStatistics();
    }

    function updateMonthToCurrent() {
        let monthSelect = document.getElementById('monthSelect');
        let currentMonth = new Date().getMonth() + 1; // Les mois JavaScript sont indexés à partir de 0

        monthSelect.value = currentMonth;
        updateStatistics();
    }

    function updateStatistics() {
        let year = document.getElementById('yearSelect').value;
        let month = document.getElementById('monthSelect').value;
        let week = document.getElementById('weekInput').value;

        fetch(`{{ route('dashboardClientStatistique') }}?year=${year}&month=${month}&week=${week}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalViewsCard').innerText = data.totalViewsCard;
                document.getElementById('monthlyViewsCount').innerText = data.monthlyViews[data.selectedMonth] ?? 0;
                document.getElementById('weeklyViewsCount').innerText = data.weeklyViews[data.selectedWeek] ?? 0;

                // Update the employer chart
                const employerData = data.employerData;
                const ctxYear = document.getElementById('yearChart').getContext('2d');
                let employe = new Chart(ctxYear, {
                    type: 'pie',
                    data: employerData,
                    options: {
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false
                            }
                        }
                    }
                });
            });
    }

    document.getElementById('monthSelect').addEventListener('change', updateStatistics);
    document.getElementById('yearSelect').addEventListener('change', updateStatistics);

    // Function to get the current week number
    Date.prototype.getWeek = function () {
        var onejan = new Date(this.getFullYear(), 0, 1);
        return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
    };
</script>

</html>
