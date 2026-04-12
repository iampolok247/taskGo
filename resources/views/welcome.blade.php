<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Go - Complete Tasks, Earn Money!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <div class="gradient-bg min-h-screen">
        <!-- Navigation -->
        <nav class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-10 w-auto">
                    <span class="text-white text-xl font-bold">Task Go</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-white hover:text-purple-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-purple-600 px-6 py-2 rounded-full font-semibold hover:bg-purple-100 transition">
                        Get Started
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="container mx-auto px-4 py-16">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 text-center lg:text-left mb-12 lg:mb-0">
                    <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
                        Complete Tasks,<br>
                        <span class="text-yellow-300">Earn Money!</span>
                    </h1>
                    <p class="text-purple-100 text-lg mb-8 max-w-md mx-auto lg:mx-0">
                        Join thousands of freelancers earning daily rewards by completing simple tasks. 
                        Start your journey to financial freedom today!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="bg-yellow-400 text-purple-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 transition transform hover:scale-105 shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>Start Earning Now
                        </a>
                        <a href="#how-it-works" class="bg-white/10 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white/20 transition border border-white/30">
                            <i class="fas fa-play mr-2"></i>How It Works
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="flex justify-center lg:justify-start gap-8 mt-12">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">10K+</div>
                            <div class="text-purple-200 text-sm">Active Freelancers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">$50K+</div>
                            <div class="text-purple-200 text-sm">Paid Out</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">500+</div>
                            <div class="text-purple-200 text-sm">Daily Tasks</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image -->
                <div class="lg:w-1/2 floating">
                    <div class="relative">
                        <div class="w-72 h-72 lg:w-96 lg:h-96 bg-white/10 rounded-3xl mx-auto backdrop-blur-sm border border-white/20 flex items-center justify-center">
                            <div class="text-center p-8">
                                <div class="w-24 h-24 bg-yellow-400 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-coins text-4xl text-purple-900"></i>
                                </div>
                                <div class="text-white text-2xl font-bold mb-2">Earn Daily</div>
                                <div class="text-purple-200">$5 - $50</div>
                            </div>
                        </div>
                        
                        <!-- Floating badges -->
                        <div class="absolute -top-4 -right-4 bg-green-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            <i class="fas fa-check mr-1"></i>100% Safe
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            <i class="fas fa-bolt mr-1"></i>Instant Payout
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Start earning in just 3 simple steps</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center p-8 rounded-2xl hover:shadow-xl transition">
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-user-plus text-3xl text-purple-600"></i>
                    </div>
                    <div class="text-purple-600 font-bold text-sm mb-2">STEP 1</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Create Account</h3>
                    <p class="text-gray-600">Sign up for free in seconds. No credit card required.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center p-8 rounded-2xl hover:shadow-xl transition">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-tasks text-3xl text-blue-600"></i>
                    </div>
                    <div class="text-blue-600 font-bold text-sm mb-2">STEP 2</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Complete Tasks</h3>
                    <p class="text-gray-600">Choose from hundreds of simple tasks and complete them.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center p-8 rounded-2xl hover:shadow-xl transition">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-3xl text-green-600"></i>
                    </div>
                    <div class="text-green-600 font-bold text-sm mb-2">STEP 3</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Get Paid</h3>
                    <p class="text-gray-600">Withdraw your earnings instantly via bKash, Nagad, or Bank.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">Why Choose Task Go?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We provide the best platform for earning money online</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl mb-4 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-purple-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">100% Secure</h3>
                    <p class="text-gray-600 text-sm">Your data and earnings are completely safe with us.</p>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl mb-4 flex items-center justify-center">
                        <i class="fas fa-bolt text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Instant Payout</h3>
                    <p class="text-gray-600 text-sm">Withdraw your money anytime, instantly.</p>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-green-100 rounded-xl mb-4 flex items-center justify-center">
                        <i class="fas fa-users text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Refer & Earn</h3>
                    <p class="text-gray-600 text-sm">Invite friends and earn commission on their tasks.</p>
                </div>
                
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl mb-4 flex items-center justify-center">
                        <i class="fas fa-headset text-yellow-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">24/7 Support</h3>
                    <p class="text-gray-600 text-sm">Our support team is always here to help you.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Ready to Start Earning?</h2>
            <p class="text-purple-100 mb-8 max-w-xl mx-auto">
                Join our community of successful earners today. It's free to sign up!
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-yellow-400 text-purple-900 px-10 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 transition transform hover:scale-105 shadow-lg">
                <i class="fas fa-rocket mr-2"></i>Get Started Free
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-10 w-auto">
                        <span class="text-white font-bold text-xl">Task Go</span>
                    </div>
                    <p class="text-sm">The most trusted platform for completing tasks and earning money online.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition">How It Works</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQs</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Refund Policy</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>© {{ date('Y') }} Task Go. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
