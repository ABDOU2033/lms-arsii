<nav class="bg-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition">
                    LMS ARSII
                </a>
            </div>

            <!-- Menu Principal -->
            <div class="hidden md:flex space-x-1 items-center">
                <a href="{{ route('courses.index') }}" 
                   class="px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('courses.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                    Tous les Cours
                </a>

                @auth
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('courses.my') }}" 
                           class="px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('courses.my') ? 'bg-blue-100 text-blue-600' : '' }}">
                            Mes Cours
                        </a>
                    @elseif(auth()->user()->isTeacher())
                        <a href="{{ route('teacher.courses') }}" 
                           class="px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('teacher.courses') ? 'bg-blue-100 text-blue-600' : '' }}">
                            Mes Cours
                        </a>
                        <a href="{{ route('teacher.courses.create') }}" 
                           class="px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                            + Nouveau Cours
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Profil et Connexion -->
            <div class="flex items-center space-x-4">
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 font-medium">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="invisible group-hover:visible absolute right-0 w-48 bg-white shadow-lg rounded-lg py-2 z-50 border border-gray-200">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">Tableau de Bord</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">Profil</a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Inscription
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
