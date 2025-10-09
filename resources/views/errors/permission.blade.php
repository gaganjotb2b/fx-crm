<!-- Not authorized-->
<div class="misc-wrapper"><a class="brand-logo" href="index.html">
        <!-- <h2 class="brand-text text-primary ms-1">Vuexy</h2> -->
    </a>
    <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
            <h2 class="mb-1">You dont have right permission! 🔐</h2>
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                        @yield('code')
                    </div>

                    <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                        @yield('message')
                    </div>
                </div>
            </div>
            <img class="img-fluid" src="{{asset('admin-assets/app-assets/images/pages/not-authorized.svg')}}" alt="Not authorized page" />
        </div>
    </div>
</div>
<!-- / Not authorized-->