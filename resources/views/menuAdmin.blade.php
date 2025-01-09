{{-- Menu Admin --}}
<div>
<nav class="bg-zinc-900 p-4 h-full w-36 fixed lg:block hidden" id="navbarNav">
    <div class="container mx-auto flex flex-col items-center">
        <a class="text-xl font-bold mb-4" href="#"></a>
        <div class="w-full lg:flex lg:flex-col lg:items-center lg:w-auto">
            <ul class="flex flex-col items-center lg:ml-0">
                <li class="nav-item mb-4">
                    <button class="mb-14 px-3 py-2 border rounded text-gray-700 border-gray-700 hidden" type="button" id="closeNavbarButton" onclick="toggleNavbar()">
                        <svg class="w-6 h-6" fill="none" stroke="#ffff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <img class="origin-center rotate-45" src="{{ asset('icons/qr.svg') }}" alt="entreprise" width="60" height="60">
                </li>
                <div class="mt-14">
                    <li class="nav-item mb-4">
                        <a class="nav-link p-2 hover:text-red-500" href="#">
                            <img src="{{ asset('icons/home.svg') }}" alt="entreprise" width="40" height="40">
                        </a>
                    </li>
                    <li class="nav-item mb-4">
                        <a class="nav-link p-2 hover:text-red-500" href="#">
                            <img src="{{ asset('icons/bar-chart.svg') }}" alt="chart" width="40" height="40">
                        </a>
                    </li>
                    <li class="nav-item mb-4">
                        <a class="nav-link p-2 hover:text-red-500" href="#">
                            <img src="{{ asset('icons/send.svg') }}" alt="message" width="30" height="30">
                        </a>
                    </li>
                </div>
            </ul>
        </div>
    </div>
</nav>

<button class="block lg:hidden px-3 py-2 my-4 border rounded text-gray-700 border-gray-700" type="button" id="navbarToggleButton" onclick="toggleNavbar()">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
    </svg>
</button>

<script>
    function toggleNavbar() {
        var navbar = document.getElementById('navbarNav');
        var toggleButton = document.getElementById('navbarToggleButton');
        var closeButton = document.getElementById('closeNavbarButton');
        navbar.classList.toggle('hidden');
        toggleButton.classList.toggle('hidden');
        closeButton.classList.toggle('hidden');
    }
</script>
</div>