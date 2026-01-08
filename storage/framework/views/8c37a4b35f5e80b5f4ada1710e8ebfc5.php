

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">Contact Us</h1>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Contact Information -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Get in Touch</h2>
                        <p class="text-gray-600 mb-6">
                            Have questions about our School Management System? We're here to help! 
                            Reach out to us using any of the methods below.
                        </p>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="text-blue-600 text-xl">📍</div>
                                <div>
                                    <h3 class="font-semibold">Address</h3>
                                    <p class="text-gray-600">123 Education Street<br>Learning City, LC 12345</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="text-blue-600 text-xl">📞</div>
                                <div>
                                    <h3 class="font-semibold">Phone</h3>
                                    <p class="text-gray-600">(555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="text-blue-600 text-xl">📧</div>
                                <div>
                                    <h3 class="font-semibold">Email</h3>
                                    <p class="text-gray-600">info@schoolsystem.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="text-blue-600 text-xl">🕒</div>
                                <div>
                                    <h3 class="font-semibold">Office Hours</h3>
                                    <p class="text-gray-600">
                                        Monday - Friday: 8:00 AM - 6:00 PM<br>
                                        Saturday: 9:00 AM - 2:00 PM<br>
                                        Sunday: Closed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Form -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Send us a Message</h2>
                        <form class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <select id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="support">Technical Support</option>
                                    <option value="demo">Request Demo</option>
                                    <option value="pricing">Pricing Information</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="mt-12 bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-2xl font-semibold mb-4">Frequently Asked Questions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold mb-2">How do I get started?</h3>
                            <p class="text-gray-600 text-sm">Simply register for an account and our team will guide you through the setup process.</p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Is training provided?</h3>
                            <p class="text-gray-600 text-sm">Yes, we provide comprehensive training for all user roles including administrators, teachers, and students.</p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">What support is available?</h3>
                            <p class="text-gray-600 text-sm">We offer 24/7 technical support via email and phone during business hours.</p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Can I request a demo?</h3>
                            <p class="text-gray-600 text-sm">Absolutely! Contact us to schedule a personalized demonstration of our system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/contact.blade.php ENDPATH**/ ?>