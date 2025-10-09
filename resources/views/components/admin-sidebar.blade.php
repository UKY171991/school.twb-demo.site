<div class="h-full overflow-y-auto">
    <ul class="space-y-0.5 text-sm">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </li>

        <!-- Theme -->
        <li>
            <a href="{{ route('admin.theme.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.theme.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                Theme
            </a>
        </li>

        <!-- Language -->
        <li>
            <a href="{{ route('admin.language.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.language.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                Language
            </a>
        </li>

        <!-- Administrator -->
        <li x-data="{ open: {{ request()->routeIs('admin.administrator.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Administrator
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.users.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Users</a></li>
                <li><a href="{{ route('admin.roles.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Roles</a></li>
                <li><a href="{{ route('admin.permissions.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Permissions</a></li>
            </ul>
        </li>

        <!-- Template -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Template
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.templates.email') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Email Template</a></li>
                <li><a href="{{ route('admin.templates.sms') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">SMS Template</a></li>
            </ul>
        </li>

        <!-- Front Office -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Front Office
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.front-office.visitors') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Visitor Book</a></li>
                <li><a href="{{ route('admin.front-office.calls') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Phone Call Log</a></li>
                <li><a href="{{ route('admin.front-office.postal') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Postal Dispatch</a></li>
            </ul>
        </li>

        <!-- Human Resource -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Human Resource
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.human-resource.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Staff Directory</a></li>
                <li><a href="{{ route('admin.human-resource.departments') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Departments</a></li>
                <li><a href="{{ route('admin.human-resource.designations') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Designations</a></li>
            </ul>
        </li>

        <!-- Manage Leave -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Manage Leave
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.leaves.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Leave Applications</a></li>
                <li><a href="{{ route('admin.leaves.types') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Leave Types</a></li>
            </ul>
        </li>

        <!-- Teacher -->
        <li>
            <a href="{{ route('admin.teachers.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.teachers.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Teacher
            </a>
        </li>

        <!-- Class Lecture -->
        <li>
            <a href="{{ route('admin.class-lectures.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.class-lectures.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Class Lecture
            </a>
        </li>

        <!-- Live Class -->
        <li>
            <a href="{{ route('admin.live-classes.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.live-classes.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Live Class
            </a>
        </li>

        <!-- Class -->
        <li>
            <a href="{{ route('admin.classes.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.classes.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Class
            </a>
        </li>

        <!-- Section -->
        <li>
            <a href="{{ route('admin.sections.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.sections.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Section
            </a>
        </li>

        <!-- Subject -->
        <li>
            <a href="{{ route('admin.subjects.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.subjects.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Subject
            </a>
        </li>

        <!-- Syllabus -->
        <li>
            <a href="{{ route('admin.syllabus.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.syllabus.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Syllabus
            </a>
        </li>

        <!-- Study Material -->
        <li>
            <a href="{{ route('admin.study-materials.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.study-materials.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Study Material
            </a>
        </li>

        <!-- Class Routine -->
        <li>
            <a href="{{ route('admin.class-routines.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.class-routines.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Class Routine
            </a>
        </li>

        <!-- Guardian -->
        <li>
            <a href="{{ route('admin.guardians.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.guardians.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Guardian
            </a>
        </li>

        <!-- Manage Exam -->
        <li x-data="{ open: {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.exam-*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Manage Exam
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.exams.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Exam Schedule</a></li>
                <li><a href="{{ route('admin.exam-schedules.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Exam Suggestion</a></li>
                <li><a href="{{ route('admin.exam-attendance.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Exam Attendance</a></li>
                <li><a href="{{ route('admin.exam-results.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Exam Mark</a></li>
            </ul>
        </li>

        <!-- Promotion -->
        <li>
            <a href="{{ route('admin.promotion.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.promotion.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Promotion
            </a>
        </li>

        <!-- Certificate -->
        <li>
            <a href="{{ route('admin.certificates.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.certificates.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Certificate
            </a>
        </li>

        <!-- Library -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    Library
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.library-books.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Books</a></li>
                <li><a href="{{ route('admin.book-issues.index') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Issue/Return</a></li>
            </ul>
        </li>

        <!-- Transport -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    Transport
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.transport.vehicles') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Vehicles</a></li>
                <li><a href="{{ route('admin.transport.routes') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Routes</a></li>
            </ul>
        </li>

        <!-- Hostel -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    </svg>
                    Hostel
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.hostel.rooms') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Rooms</a></li>
                <li><a href="{{ route('admin.hostel.members') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Members</a></li>
            </ul>
        </li>

        <!-- Message -->
        <li>
            <a href="{{ route('admin.messages.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.messages.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Message
            </a>
        </li>

        <!-- Mail & SMS -->
        <li>
            <a href="{{ route('admin.mail-sms.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.mail-sms.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Mail & SMS
            </a>
        </li>

        <!-- Complain -->
        <li>
            <a href="{{ route('admin.complains.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.complains.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Complain
            </a>
        </li>

        <!-- Announcement -->
        <li>
            <a href="{{ route('admin.announcements.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.announcements.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Announcement
            </a>
        </li>

        <!-- Event -->
        <li>
            <a href="{{ route('admin.events.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.events.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Event
            </a>
        </li>

        <!-- Payroll -->
        <li>
            <a href="{{ route('admin.payroll.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.payroll.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Payroll
            </a>
        </li>

        <!-- Accounting -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Accounting
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.accounting.income') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Income</a></li>
                <li><a href="{{ route('admin.accounting.expense') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Expense</a></li>
            </ul>
        </li>

        <!-- Report -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Report
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.reports.students') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Student Report</a></li>
                <li><a href="{{ route('admin.reports.attendance') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Attendance Report</a></li>
                <li><a href="{{ route('admin.reports.financial') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Financial Report</a></li>
            </ul>
        </li>

        <!-- Media Gallery -->
        <li>
            <a href="{{ route('admin.media-gallery.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('admin.media-gallery.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Media Gallery
            </a>
        </li>

        <!-- Manage Frontend -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-300 hover:bg-gray-700 rounded">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Manage Frontend
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                <li><a href="{{ route('admin.frontend.pages') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Pages</a></li>
                <li><a href="{{ route('admin.frontend.menus') }}" class="block px-3 py-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded text-xs">Menus</a></li>
            </ul>
        </li>

        <!-- Profile -->
        <li>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('profile.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-700' }} rounded">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile
            </a>
        </li>
    </ul>
</div>

