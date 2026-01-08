@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">About Our School Management System</h1>
                
                <div class="prose max-w-none">
                    <p class="text-lg mb-6">
                        Our School Management System is a comprehensive platform designed to streamline educational 
                        administration and enhance the learning experience for students, teachers, and administrators.
                    </p>
                    
                    <h2 class="text-2xl font-semibold mb-4">Our Mission</h2>
                    <p class="mb-6">
                        To provide educational institutions with powerful, user-friendly tools that simplify 
                        administrative tasks, improve communication, and support academic excellence.
                    </p>
                    
                    <h2 class="text-2xl font-semibold mb-4">Key Features</h2>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Comprehensive student information management</li>
                        <li>Teacher portal for grade management and class oversight</li>
                        <li>Administrative dashboard with real-time analytics</li>
                        <li>Secure role-based access control</li>
                        <li>Course and classroom management</li>
                        <li>Grade tracking and academic reporting</li>
                        <li>Enrollment management system</li>
                    </ul>
                    
                    <h2 class="text-2xl font-semibold mb-4">Technology Stack</h2>
                    <p class="mb-6">
                        Built with modern web technologies including Laravel 12, PHP 8.2, SQLite database, 
                        and Tailwind CSS for a responsive and intuitive user interface.
                    </p>
                    
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold mb-3">Get Started Today</h3>
                        <p class="mb-4">
                            Ready to transform your educational institution's management? 
                            Contact us to learn more about implementation and training.
                        </p>
                        <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection