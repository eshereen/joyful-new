<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Joyful' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Performance optimizations -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">



    <!-- Livewire Styles -->
    @livewireStyles
   </link>
   <script src="https://cdn.jsdelivr.net/npm/medium-zoom@1.0.6/dist/medium-zoom.min.js"></script>

<style>
    .slider-wrapper {
        width: 100%;
        overflow: hidden !important;
        position: relative;
    }

    .product-slider {
        display: flex !important;
        flex-wrap: nowrap !important;
        width: max-content !important;
        gap: 1.5rem;
        animation: slideAnimation 30s linear infinite;
        padding: 1rem 0;
    }
    	[x-cloak] { display: none; }

			.grid-indigo {
				background-image: radial-gradient(#e87509 2px, transparent 2px);
				background-size: 16px 16px;
			}

    .product-slider > div {
        flex: 0 0 auto !important;
        width: auto !important;
    }

    @keyframes slideAnimation {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    .product-slider:hover {
        animation-play-state: paused;
    }
</style>

    <!-- Preload critical CSS -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!--Favicons-->


<!--google fonts-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">

     <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'something': ['"something"', 'Arial', 'Helvetica', 'sans-serif'],
                        'cinzel': ['"Cinzel"', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>



h1,h2,h3,h4,h5,h6{
    font-family: "something", 'Arial', 'Helvetica', sans-serif;
    font-optical-sizing: auto;
    font-weight: 800;
    font-style: normal;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
p,a,span,li,ul,ol,button{
    font-family: "Cinzel", serif;
    font-optical-sizing: auto;
    font-weight: 300;
    font-style: normal;
    text-transform: uppercase;
}
    </style>

</head>
<body class="bg-white text-gray-950 antialiased overflow-x-hidden">
        <!-- Loader Overlay -->
<div
x-data="{ show: true }"
x-init="window.addEventListener('load', () => { show = false })"
x-show="show"
x-transition:leave="transition-opacity duration-700"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-50 flex items-center justify-center bg-white"
style="background: rgba(255,255,255,0.95);"
>
<img src="/imgs/logo.png" alt="Loading..." class="w-64 animate-pulse">
</div>
    @include('layouts.navbar')
    <!-- Notification System -->
    <div id="notification-container" class="fixed top-4 right-4 z-[9999] p-4 text-white" style="pointer-events: none;"></div>

    @yield('content')

    @include('layouts.footer')

    <!-- Livewire Scripts (includes Alpine.js) -->
    @livewireScripts
    <!-- Performance optimization script -->
    <script>
        // Lazy loading optimization
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for lazy loading
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            // Observe all lazy images
            document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                img.classList.add('loading-lazy');
                imageObserver.observe(img);
            });

            // Preload critical images
            const criticalImages = document.querySelectorAll('img[fetchpriority="high"]');
            criticalImages.forEach(img => {
                if (img.src) {
                    const link = document.createElement('link');
                    link.rel = 'preload';
                    link.as = 'image';
                    link.href = img.src;
                    document.head.appendChild(link);
                }
            });

            // Product image hover effect (JavaScript backup)
            function initializeHoverEffect() {
                const containers = document.querySelectorAll('.product-image-container');

                containers.forEach((container) => {
                    const mainImage = container.querySelector('.main-image');
                    const galleryImage = container.querySelector('.gallery-image');

                    if (mainImage && galleryImage) {
                        // Ensure gallery image is hidden initially
                        galleryImage.style.opacity = '0';
                        galleryImage.style.display = 'block';

                        // Remove existing event listeners to prevent duplicates
                        if (container._hoverEnter) {
                            container.removeEventListener('mouseenter', container._hoverEnter);
                        }
                        if (container._hoverLeave) {
                            container.removeEventListener('mouseleave', container._hoverLeave);
                        }

                        // Create new event handlers
                        container._hoverEnter = function() {
                            mainImage.style.opacity = '0';
                            galleryImage.style.opacity = '1';
                        };

                        container._hoverLeave = function() {
                            mainImage.style.opacity = '1';
                            galleryImage.style.opacity = '0';
                        };

                        // Add event listeners
                        container.addEventListener('mouseenter', container._hoverEnter);
                        container.addEventListener('mouseleave', container._hoverLeave);
                    }
                });
            }

            // Initialize hover effect
            initializeHoverEffect();

            // Re-initialize after Livewire updates
            document.addEventListener('livewire:navigated', initializeHoverEffect);
            document.addEventListener('livewire:updated', initializeHoverEffect);
            document.addEventListener('livewire:load', initializeHoverEffect);
        });
    </script>

    <!-- Notification System Script -->
    <script>
                // Global function to show notifications
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');

            if (!container) {
                return;
            }

            const notification = document.createElement('div');
            notification.className = `notification mb-4 p-4 rounded-lg shadow-lg transform translate-x-full transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;

            // Create a span element for the text
            const textSpan = document.createElement('span');
            textSpan.textContent = message;
            textSpan.style.color = 'white';
            textSpan.style.fontSize = '15px';
            textSpan.style.fontWeight = 'bold';
            textSpan.style.display = 'block';
            textSpan.style.textAlign = 'center';

            notification.appendChild(textSpan);

            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';

            notification.style.backgroundColor = type === 'success' ? '#10B981' : '#EF4444';
            notification.style.padding = '16px';
            notification.style.marginBottom = '16px';

            container.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Hide and remove notification
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (container.contains(notification)) {
                        container.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

                // CSRF and Session Management
        let sessionRefreshTimer;

        function refreshSession() {
            fetch('/currency/current', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).catch(() => {
                // Silently fail - this is just to keep session alive
            });
        }

        function startSessionRefresh() {
            // Refresh session every 2.5 hours (before 3 hour expiry)
            sessionRefreshTimer = setInterval(refreshSession, 150 * 60 * 1000);
        }

        function updateCSRFToken() {
            fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.csrf_token) {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    // Update Livewire's CSRF token if available
                    if (window.Livewire && window.Livewire.all) {
                        window.Livewire.all().forEach(component => {
                            if (component.csrf) {
                                component.csrf = data.csrf_token;
                            }
                        });
                    }
                }
            })
            .catch(() => {
                // CSRF token refresh failed - reload page
                window.location.reload();
            });
        }

        // Listen for Livewire notification events
        document.addEventListener('livewire:init', () => {
            // Start session refresh timer
            startSessionRefresh();

            // Enhanced Livewire error handling for live server
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, response, content }) => {
                    console.log('Livewire request failed:', { status, response, content });

                    if (status === 419 || (response && response.includes('CSRF')) || (response && response.includes('expired'))) {
                        // Show user-friendly message
                        showNotification('Your session has expired. Refreshing page...', 'error');

                        // Try to get new CSRF token from response
                        try {
                            const data = JSON.parse(content);
                            if (data.csrf_token) {
                                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                            }
                        } catch (e) {
                            console.log('Could not parse CSRF response');
                        }

                        // CSRF token expired - refresh and retry
                        updateCSRFToken();
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        return false; // Prevent default error handling
                    }

                    // Handle other errors
                    if (status >= 500) {
                        showNotification('Server error occurred. Please try again.', 'error');
                        return false;
                    }
                });
            });

            // Global currency change listener
            window.addEventListener('currency-changed', function(event) {
                console.log('🌍 Global currency change detected:', event.detail);
                if (window.Livewire) {
                    // Dispatch to all Livewire components
                    window.Livewire.dispatch('currency-changed', event.detail);
                    window.Livewire.dispatch('global-currency-changed', event.detail);

                    // Force refresh all components
                    window.Livewire.all().forEach(component => {
                        if (component.$refresh) {
                            component.$refresh();
                        }
                    });
                }
            });

            // Additional Livewire debugging for live server
            Livewire.hook('component.initialized', (component) => {
                console.log('Livewire component initialized:', component.fingerprint.name);
            });

            Livewire.hook('element.updating', (fromEl, toEl, component) => {
                console.log('Livewire updating element for:', component.fingerprint.name);
            });
            Livewire.on('showNotification', (data) => {
                let message, type;

                if (Array.isArray(data)) {
                    message = data[0]?.message || data[0];
                    type = data[0]?.type || 'success';
                } else if (typeof data === 'object') {
                    message = data.message;
                    type = data.type || 'success';
                } else {
                    message = data;
                    type = 'success';
                }

                showNotification(message, type);
            });

            // Handle stock error notifications with action buttons
            Livewire.on('showStockError', (data) => {
                const container = document.getElementById('notification-container');
                if (!container) return;

                const notification = document.createElement('div');
                notification.className = 'notification mb-4 p-4 rounded-lg shadow-lg transform translate-x-full transition-all duration-300 bg-red-500';

                notification.style.zIndex = '9999';
                notification.style.minWidth = '400px';
                notification.style.border = '3px solid #DC2626';
                notification.style.backgroundColor = '#EF4444';
                notification.style.padding = '20px';
                notification.style.marginBottom = '16px';

                // Create message container
                const messageDiv = document.createElement('div');
                messageDiv.style.color = 'white';
                messageDiv.style.fontSize = '16px';
                messageDiv.style.fontWeight = 'bold';
                messageDiv.style.marginBottom = '15px';
                messageDiv.style.textAlign = 'center';
                messageDiv.textContent = data.message || 'Stock error occurred';

                // Create action buttons container
                const buttonsDiv = document.createElement('div');
                buttonsDiv.style.display = 'flex';
                buttonsDiv.style.justifyContent = 'center';
                buttonsDiv.style.gap = '10px';

                // View Cart button
                const cartButton = document.createElement('button');
                cartButton.textContent = 'View Cart';
                cartButton.className = 'px-4 py-2 bg-white text-red-600 font-bold rounded hover:bg-white transition-colors';
                cartButton.onclick = () => {
                    window.location.href = '/cart';
                };

                // Continue Shopping button
                const shopButton = document.createElement('button');
                shopButton.textContent = 'Continue Shopping';
                shopButton.className = 'px-4 py-2 bg-white text-red-600 font-bold rounded hover:bg-white transition-colors';
                shopButton.onclick = () => {
                    window.location.href = '/';
                };

                // Close button
                const closeButton = document.createElement('button');
                closeButton.textContent = '✕';
                closeButton.className = 'px-3 py-2 bg-white text-red-600 font-bold rounded hover:bg-white transition-colors';
                closeButton.style.position = 'absolute';
                closeButton.style.top = '10px';
                closeButton.style.right = '10px';
                closeButton.onclick = () => {
                    notification.style.transform = 'translateX(100%)';
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (container.contains(notification)) {
                            container.removeChild(notification);
                        }
                    }, 300);
                };

                notification.style.position = 'relative';
                buttonsDiv.appendChild(cartButton);
                buttonsDiv.appendChild(shopButton);
                notification.appendChild(messageDiv);
                notification.appendChild(buttonsDiv);
                notification.appendChild(closeButton);

                container.appendChild(notification);

                // Show notification
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                    notification.style.transform = 'translateX(0)';
                }, 100);

                // Auto-hide after 10 seconds (longer for stock errors)
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (container.contains(notification)) {
                            container.removeChild(notification);
                        }
                    }, 300);
                }, 10000);
            });
        });
    </script>
</body>
</html>
