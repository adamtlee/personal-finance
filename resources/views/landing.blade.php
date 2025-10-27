<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finance Manager - Take Control of Your Financial Life</title>
    <meta name="description" content="Manage your accounts, track subscriptions, and gain insights into your financial health with our comprehensive finance management platform.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-gray-900">💰 Finance Manager</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if(auth()->check())
                        <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="/admin/login" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Sign In
                        </a>
                        <a href="/admin/login" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Get Started
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-blue-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Take Control of Your
                    <span class="text-blue-600">Financial Life</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Manage your accounts, track subscriptions, and gain valuable insights into your financial health with our comprehensive finance management platform.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if(auth()->check())
                        <a href="/admin" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="/admin/login" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors">
                            Start Managing Your Finances
                        </a>
                    @endif
                    <a href="#features" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-colors">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Everything You Need to Manage Your Finances
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Our comprehensive platform provides all the tools you need to take control of your financial life.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Account Management -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Account Management</h3>
                    <p class="text-gray-600 mb-4">
                        Track all your financial accounts in one place - checking, savings, credit cards, investments, and more.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Multiple account types supported</li>
                        <li>• Real-time balance tracking</li>
                        <li>• Institution linking</li>
                    </ul>
                </div>

                <!-- Subscription Tracking -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Subscription Tracking</h3>
                    <p class="text-gray-600 mb-4">
                        Never lose track of your recurring subscriptions again. Monitor costs, billing cycles, and upcoming payments.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Weekly, monthly, and yearly billing</li>
                        <li>• Category organization</li>
                        <li>• Payment reminders</li>
                    </ul>
                </div>

                <!-- Financial Insights -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Financial Insights</h3>
                    <p class="text-gray-600 mb-4">
                        Get a clear picture of your financial health with comprehensive dashboards and analytics.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Visual spending breakdowns</li>
                        <li>• Monthly spending summaries</li>
                        <li>• Financial health indicators</li>
                    </ul>
                </div>

                <!-- Secure & Private -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Secure & Private</h3>
                    <p class="text-gray-600 mb-4">
                        Your financial data is protected with enterprise-grade security and privacy controls.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Bank-level encryption</li>
                        <li>• Secure authentication</li>
                        <li>• Data privacy protection</li>
                    </ul>
                </div>

                <!-- Easy to Use -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Easy to Use</h3>
                    <p class="text-gray-600 mb-4">
                        Intuitive interface designed for everyone, from financial beginners to experts.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Clean, modern interface</li>
                        <li>• Mobile-responsive design</li>
                        <li>• Quick setup process</li>
                    </ul>
                </div>

                <!-- Always Available -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Always Available</h3>
                    <p class="text-gray-600 mb-4">
                        Access your financial data anytime, anywhere with our reliable cloud-based platform.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• 24/7 availability</li>
                        <li>• Cloud synchronization</li>
                        <li>• Cross-device access</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Ready to Take Control of Your Finances?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Join thousands of users who have already transformed their financial management with our platform.
            </p>
            @if(auth()->check())
                <a href="/admin" class="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                    Go to Dashboard
                </a>
            @else
                <a href="/admin/login" class="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                    Get Started Today
                </a>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4">💰 Finance Manager</h3>
                    <p class="text-gray-400 mb-4">
                        Your comprehensive solution for managing accounts, tracking subscriptions, and gaining financial insights.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Features</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Account Management</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Subscription Tracking</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Financial Insights</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Get Started</h4>
                    <ul class="space-y-2 text-gray-400">
                        @if(auth()->check())
                            <li><a href="/admin" class="hover:text-white transition-colors">Dashboard</a></li>
                        @else
                            <li><a href="/admin/login" class="hover:text-white transition-colors">Sign In</a></li>
                        @endif
                        <li><a href="#features" class="hover:text-white transition-colors">Learn More</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Finance Manager. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>