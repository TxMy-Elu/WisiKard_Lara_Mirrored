<nav class="bg-zinc-900 p-4 h-full w-36 fixed">
    <div class="container mx-auto flex flex-col items-center">
        <a class="text-xl font-bold mb-4" href="#"></a>
        <div class="w-full lg:flex lg:flex-col lg:items-center lg:w-auto" id="navbarNav">
            <ul class="flex flex-col items-center lg:ml-0">
                <li class="nav-item mb-4">
                    <img class="origin-center rotate-45" src="{{ asset('icons/qr.svg') }}" alt="entreprise" width="60"
                         height="60">
                </li>
                <div class="mt-14">
                    <li class="nav-item mb-4">
                        <a class="nav-link p-2 hover:opacity-75" href="#">
                            <img class="" src="{{ asset('icons/home.svg') }}" alt="entreprise" width="40" height="40">
                        </a>
                    </li>
                    <li class="nav-item mb-4">
                        <a class="nav-link p-2 hover:opacity-75" href="#">
                            <img src="{{ asset('icons/bar-chart.svg') }}" alt="chart" width="40" height="40">
                        </a>
                    </li>
                    <li class="nav-item mb-4">
                        <a class="nav-link p-2 hover:opacity-75" href="#">
                            <img src="{{ asset('icons/send.svg') }}" alt="message" width="30" height="30">
                        </a>
                    </li>
                </div>
            </ul>
        </div>
    </div>
</nav>