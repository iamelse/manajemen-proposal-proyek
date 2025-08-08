<nav
   class="fixed top-0 left-0 z-50 w-full transition-shadow bg-white border-gray-200 dark:bg-gray-800"
   :class="{ 'shadow-sm': scrollTop }"
   x-data
   @scroll.window="scrollTop = window.scrollY > 10"
   > <!-- Add fixed class to make it sticky -->
   <div class="max-w-6xl mx-auto px-6 flex flex-wrap items-center justify-between py-2.5 md:py-3.5 lg:py-4.5">
      <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
         <img src="{{ asset('logo/iamelse-logo-1.png')  }}" class="h-10 rounded" alt="Iamelse Logo" />
         <span class="self-center block text-2xl font-semibold sm:hidden md:hidden lg:hidden whitespace-nowrap dark:text-white">iamelse</span>
      </a>
      <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse py-1.5">
         @auth
         <button
            @click="userNavbarToggle = !userNavbarToggle"
            type="button"
            class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button"
            aria-expanded="false"
            data-dropdown-toggle="user-dropdown"
            data-dropdown-placement="bottom"
            >
         <span class="sr-only">Open user menu</span>
         <img class="w-10 h-10 overflow-hidden rounded-full" src="{{ getUserImageProfilePath(Auth::user()) }}" alt="user photo" />
         </button>
         <div
            :class="userNavbarToggle ? 'block' : 'hidden'"
            class="absolute right-0 w-48 mt-2 bg-white divide-y divide-gray-100 rounded-lg shadow-lg top-full dark:bg-gray-700 dark:divide-gray-600"
            id="user-dropdown"
            >
            <div class="px-3 py-3">
               <span class="block text-sm text-gray-900 dark:text-gray-300">{{ Auth::user()->name }}</span>
               <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
            </div>
            <ul class="py-2" aria-labelledby="user-menu-button">
               <li>
                  <a href="{{ route('be.dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
               </li>
               <li x-data>
                  <a href="#"
                     @click.prevent="$refs.logoutForm.submit()"
                     class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                  Sign out
                  </a>
                  <form x-ref="logoutForm" action="{{ route('auth.logout') }}" method="POST" class="hidden">
                     @csrf
                  </form>
               </li>
            </ul>
         </div>
         @endauth
         <button
               @click="sidebarToggle = !sidebarToggle"
               data-collapse-toggle="navbar-user"
               type="button"
               class="inline-flex items-center justify-center text-sm text-gray-500 rounded-lg w-9 h-9 md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
               aria-controls="navbar-user"
               aria-expanded="false"
            >
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
         </button>
      </div>
      <div :class="sidebarToggle ? 'block' : 'hidden'" class="items-center justify-between w-full md:flex md:w-auto md:order-1" id="navbar-user">
          @php
              $menuItems = [
                  [
                      'active' => 'fe.home',
                      'route' => 'fe.home.index',
                      'label' => 'Home',
                  ]
              ];
          @endphp

          <ul class="flex flex-col p-4 mt-4 font-medium border border-gray-100 rounded-lg md:p-0 bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-900 md:dark:bg-gray-800 dark:border-gray-700">
              @foreach ($menuItems as $item)
                  @php
                      $isActive = request()->routeIs($item['active'] . '*');
                  @endphp
                  <li>
                      <a href="{{ route($item['route']) }}"
                         class="block py-2 px-3 rounded-sm md:bg-transparent md:p-0
                      {{ $isActive
                          ? 'text-white bg-blue-600 md:text-blue-600 md:dark:text-blue-600'
                          : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-gray-300 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent' }}">
                          {{ $item['label'] }}
                      </a>
                  </li>
              @endforeach
          </ul>
      </div>
   </div>
</nav>
