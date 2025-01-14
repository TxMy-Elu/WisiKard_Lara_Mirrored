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
    @include('menu.menuClient')

    <div class="flex-1 md:ml-24 p-6">
        <div class="w-full md:w-1/3 mx-auto p-6 bg-white rounded-lg border shadow-md mb-6 flex flex-col justify-between items-center">
            <div class="mb-4 w-full text-center">
                <label for="yearSelect" class="block text-2xl font-bold text-gray-700">Sélectionnez l'année</label>
            </div>
            <form id="yearForm" action="{{ route('dashboardClientStatistique') }}" method="get" class="flex items-center w-full justify-center">
                <select name="year" id="yearSelect" class="custom-select w-32 text-center" onchange="this.form.submit()">
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
                    <h1 class="text-7xl font-bold text-red-900">{{ $totalViewsCard }}</h1>
                </div>
            </div>


        </div>
    </div>
</div>

</body>
</html>