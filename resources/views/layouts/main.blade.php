<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
     <title>Nour Bookstore</title>
     @vite(['resources/css/app.css'])
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

     {{-- cairo font - from google --}}
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
     {{-- cairo font - from google --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <style>
          body {
               font-family: 'Cairo', sans-serif;
               background-color: #f0f0f0;
          }

                  .score {
            display: block;
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }
        .score-wrap {
            display: inline-block;
            position: relative;
            height: 19px;
        }
        .score .stars-active {
            color: #FFCA00;
            position: relative;
            z-index: 10;
            display: block;
            overflow: hidden;
            white-space: nowrap;
        }
        .score .stars-inactive {
            color: lightgrey;
            position: absolute;
            top: 0;
            left: 0;
        }

        .rating {
            overflow: hidden;
            display: inline-block;
            position: relative;
            font-size: 20px;
        }
        .rating-star {
            padding: 0 5px;
            margin: 0;
            cursor: pointer;
            display: block;
            float: left;
        }
        .rating-star:after {
            position: relative;
            font-family: "Font Awesome 5 Free";
            content: '\f005';
            color: lightgrey;
        }
        .rating-star.checked ~ .rating-star:after,
        .rating-star.checked:after {
            content: '\f005';
            color: #FFCA00;
        }
        .rating:hover .rating-star:after {
            content: '\f005';
            color: lightgrey;
        }
        .rating-star:hover ~ .rating-star:after,
        .rating .rating-star:hover:after {
            content: '\f005';
            color: #FFCA00;
        }
     </style>
     @yield('head')
</head>

<body>

     <div>
          <nav class="navbar navbar-expand-lg bg-white">
               <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">Nour Bookstore</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                         data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                         aria-expanded="false" aria-label="Toggle navigation">
                         <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                         <ul class="navbar-nav mx-auto">
                              <li class="nav-item">
                                   <a href="{{ route('gallery.categories.index') }}" class="nav-link"><i class="fas fa-list"></i> Categories</a>
                              </li>
                              <li class="nav-item">
                                   <a href="{{ route('gallery.publishers.index') }}" class="nav-link"><i class="fas fa-table"></i> Publishers</a>
                              </li>
                              <li class="nav-item">
                                   <a href="{{ route('gallery.authors.index') }}" class="nav-link"><i class="fas fa-pen"></i> Authors</a>
                              </li>
                              <li class="nav-item">
                                   <a href="#" class="nav-link"><i class="fas fa-basket-shopping"></i> My
                                        purchases</a>
                              </li>
                         </ul>

                         <ul class="navbar-nav mx-auto">
                              @guest
                                   <li class="nav-item">
                                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                                   </li>
                                   @if (Route::has('register'))
                                        <li class="nav-item">
                                             <a href="{{ route('register') }}" class="nav-link">Register</a>
                                        </li>
                                   @endif
                              @else
                                   <li class="nav-item dropdown justify-content-left">
                                        <a href="#" id="navbarDropdown" class="nav-link" data-bs-toggle="dropdown">
                                             <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                                  class="w-8 h-8 rounded-full object-cover">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right px-2 text-left mt-2">
                                            @can('update-books')
                                                <a href="{{ route('admin.index') }}" class="dropdown-item">Dashboard</a>
                                            @endcan
                                            <div class="pt-4 pb-1 border-t border-gray-200">
                                                <div class="flex items-center px-4">

                                                    <div>
                                                        <div class="font-medium text-base text-gray-800">
                                                                {{ Auth::user()->name }}</div>

                                                    </div>
                                                </div>

                                                <div class="mt-3 space-y-1">
                                                    <!-- Account Management -->
                                                    <x-jet-responsive-nav-link href="{{ route('profile.show') }}"
                                                        :active="request()->routeIs('profile.show')">
                                                        {{ __('Profile') }}
                                                    </x-jet-responsive-nav-link>

                                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                                        <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}"
                                                                :active="request()->routeIs('api-tokens.index')">
                                                                {{ __('API Tokens') }}
                                                        </x-jet-responsive-nav-link>
                                                    @endif

                                                    <!-- Authentication -->
                                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                                        @csrf

                                                        <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                                                {{-- @click.prevent="$root.submit();" --}}
                                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                                {{ __('Log Out') }}
                                                        </x-jet-responsive-nav-link>
                                                    </form>

                                                    <!-- Team Management -->
                                                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                                        <div class="border-t border-gray-200"></div>

                                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                                                {{ __('Manage Team') }}
                                                        </div>

                                                        <!-- Team Settings -->
                                                        <x-jet-responsive-nav-link
                                                                href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                                                :active="request()->routeIs('teams.show')">
                                                                {{ __('Team Settings') }}
                                                        </x-jet-responsive-nav-link>

                                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                                <x-jet-responsive-nav-link href="{{ route('teams.create') }}"
                                                                    :active="request()->routeIs('teams.create')">
                                                                    {{ __('Create New Team') }}
                                                                </x-jet-responsive-nav-link>
                                                        @endcan

                                                        <div class="border-t border-gray-200"></div>

                                                        <!-- Team Switcher -->
                                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                                                {{ __('Switch Teams') }}
                                                        </div>

                                                        @foreach (Auth::user()->allTeams() as $team)
                                                                <x-jet-switchable-team :team="$team"
                                                                    component="jet-responsive-nav-link" />
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                   </li>
                              @endguest
                         </ul>
                    </div>
               </div>
          </nav>

          <main class="py-4">
            @yield('content')
          </main>

     </div>

     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
     <script src="https://kit.fontawesome.com/495c8a12a7.js" crossorigin="anonymous"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     @yield('script')
</body>

</html>
