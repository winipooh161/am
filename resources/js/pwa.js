/**
 * PWA functionality for the application
 */
document.addEventListener('DOMContentLoaded', function() {
    // PWA Installation
    initPwaInstallation();
    
    // Offline content handling
    handleOfflineContent();
});

/**
 * Initialize PWA installation functionality
 */
function initPwaInstallation() {
    const installContainer = document.getElementById('pwa-install-widget');
    const installButton = document.getElementById('install-pwa');
    const closeButton = document.querySelector('.close-pwa-widget');
    
    if (!installContainer) return;
    
    let deferredPrompt;
    let deviceType = detectDeviceType();
    
    // Set widget content based on device type
    setWidgetContent(deviceType);
    
    // Setup event listeners
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            dismissWidget();
        });
    }
    
    if (installButton) {
        installButton.addEventListener('click', () => {
            promptInstall();
        });
    }
    
    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt = e;
        
        // Show the install button if not on iOS (iOS doesn't support beforeinstallprompt)
        if (deviceType !== 'ios') {
            installButton.style.display = 'flex';
        }
        
        // Show the widget unless recently dismissed
        if (!wasRecentlyDismissed()) {
            showWidget();
        }
    });
    
    // Handle iOS separately (no beforeinstallprompt support)
    if (deviceType === 'ios' && !wasRecentlyDismissed()) {
        setTimeout(() => {
            showWidget();
        }, 3000);
    }
    
    // When the PWA is installed
    window.addEventListener('appinstalled', (e) => {
        // Hide the widget
        hideWidget();
        
        // Log the installation to analytics
        if (typeof(ym) !== 'undefined') {
            ym(100639873, 'reachGoal', 'pwa_installed');
        }
        
        console.log('PWA was installed');
    });
    
    /**
     * Prompt the user to install the PWA
     */
    function promptInstall() {
        if (!deferredPrompt) {
            console.log('Installation prompt not available');
            return;
        }
        
        // Show the install prompt
        deferredPrompt.prompt();
        
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
                if (typeof(ym) !== 'undefined') {
                    ym(100639873, 'reachGoal', 'pwa_install_accepted');
                }
            } else {
                console.log('User dismissed the install prompt');
                if (typeof(ym) !== 'undefined') {
                    ym(100639873, 'reachGoal', 'pwa_install_dismissed');
                }
            }
            
            // Clear the deferredPrompt variable
            deferredPrompt = null;
        });
    }
    
    /**
     * Show the installation widget
     */
    function showWidget() {
        installContainer.style.display = 'block';
        setTimeout(() => {
            installContainer.classList.add('show');
        }, 10);
        
        // Log to analytics
        if (typeof(ym) !== 'undefined') {
            ym(100639873, 'reachGoal', 'pwa_widget_shown');
        }
    }
    
    /**
     * Hide the installation widget
     */
    function hideWidget() {
        installContainer.classList.remove('show');
        setTimeout(() => {
            installContainer.style.display = 'none';
        }, 300);
    }
    
    /**
     * Dismiss the widget and save the timestamp
     */
    function dismissWidget() {
        localStorage.setItem('pwaInstallDismissed', Date.now().toString());
        hideWidget();
        
        // Log to analytics
        if (typeof(ym) !== 'undefined') {
            ym(100639873, 'reachGoal', 'pwa_widget_dismissed');
        }
    }
    
    /**
     * Check if the widget was recently dismissed
     */
    function wasRecentlyDismissed() {
        const dismissedTime = localStorage.getItem('pwaInstallDismissed');
        if (!dismissedTime) return false;
        
        const now = Date.now();
        const dismissedAt = parseInt(dismissedTime);
        const dayInMs = 24 * 60 * 60 * 1000;
        
        // Show again after 3 days
        return (now - dismissedAt) < (3 * dayInMs);
    }
}

/**
 * Set widget content based on device type
 */
function setWidgetContent(deviceType) {
    const contentElement = document.querySelector('.pwa-widget-content');
    const deviceIconElement = document.querySelector('.device-icon');
    const installButton = document.getElementById('install-pwa');
    
    if (!contentElement || !deviceIconElement) return;
    
    // Set device icon
    let deviceIcon = 'fas fa-mobile-alt';
    
    switch(deviceType) {
        case 'ios':
            deviceIcon = 'fab fa-apple';
            break;
        case 'android':
            deviceIcon = 'fab fa-android';
            break;
        case 'windows':
            deviceIcon = 'fab fa-windows';
            break;
    }
    
    deviceIconElement.className = deviceIcon + ' device-icon';
    
    // Set content based on device type
    switch(deviceType) {
        case 'ios':
            contentElement.innerHTML = `
                <p>Установите наше приложение на свой iPhone/iPad:</p>
                <ol>
                    <li>Нажмите на <strong><i class="fas fa-share"></i> Поделиться</strong> в Safari</li>
                    <li>Прокрутите вниз и нажмите <strong>"На экран «Домой»"</strong></li>
                    <li>Нажмите <strong>"Добавить"</strong> в верхнем правом углу</li>
                </ol>
            `;
            // No install button for iOS
            if (installButton) installButton.style.display = 'none';
            break;
            
        case 'android':
            contentElement.innerHTML = `
                <p>Установите наше приложение на свой Android:</p>
                <p>Нажмите на кнопку "Установить приложение" ниже</p>
            `;
            break;
            
        case 'windows':
            contentElement.innerHTML = `
                <p>Установите наше приложение на свой компьютер:</p>
                <p>Нажмите на кнопку "Установить приложение" ниже или на значок установки <i class="fas fa-plus-square"></i> в адресной строке браузера</p>
            `;
            break;
            
        default:
            contentElement.innerHTML = `
                <p>Установите наше приложение для быстрого доступа:</p>
                <p>Нажмите на кнопку "Установить приложение" ниже</p>
            `;
    }
}

/**
 * Detect device type based on user agent
 */
function detectDeviceType() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    
    // Check for iOS
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return 'ios';
    }
    
    // Check for Android
    if (/android/i.test(userAgent)) {
        return 'android';
    }
    
    // Check for Windows
    if (/Windows NT/.test(userAgent)) {
        return 'windows';
    }
    
    return 'other';
}

/**
 * Handle offline content functionality
 */
function handleOfflineContent() {
    // Check if we're online
    const updateOnlineStatus = () => {
        const condition = navigator.onLine ? "online" : "offline";
        console.log(`App is now ${condition}`);
        
        // Show message if offline
        const offlineMessage = document.getElementById('offline-message');
        if (offlineMessage) {
            if (condition === 'offline') {
                offlineMessage.classList.remove('d-none');
            } else {
                offlineMessage.classList.add('d-none');
            }
        }
    }
    
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    
    // Run on initial load
    updateOnlineStatus();
    
    // Cache most recently viewed recipes for offline access
    if ('localStorage' in window) {
        const pwaData = document.getElementById('pwa-data');
        if (pwaData && pwaData.dataset.recipe) {
            try {
                const recipeData = JSON.parse(pwaData.dataset.recipe);
                
                // Get existing recipes cache
                let recentRecipes = JSON.parse(localStorage.getItem('recent_recipes') || '[]');
                
                // Check if this recipe is already in the cache
                const existingIndex = recentRecipes.findIndex(recipe => recipe.id === recipeData.id);
                
                if (existingIndex > -1) {
                    // Update the timestamp and move to front
                    recentRecipes.splice(existingIndex, 1);
                }
                
                // Add to beginning of array
                recentRecipes.unshift(recipeData);
                
                // Keep only the 10 most recent
                recentRecipes = recentRecipes.slice(0, 10);
                
                // Save to localStorage
                localStorage.setItem('recent_recipes', JSON.stringify(recentRecipes));
                
                console.log('Recipe cached for offline access');
            } catch (e) {
                console.error('Error caching recipe:', e);
            }
        }
    }
}

// Регистрация Service Worker для PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
                console.log('Service Worker зарегистрирован с областью:', registration.scope);
            })
            .catch(error => {
                console.error('Ошибка регистрации Service Worker:', error);
            });
    });
}

// PWA скрипт для установки приложения
let deferredPrompt;

// Обнаружение устройства для отображения правильных инструкций
function detectDevice() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    
    if (/android/i.test(userAgent)) {
        return 'android';
    }
    
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return 'ios';
    }
    
    return 'windows';
}

// Инициализация виджета установки PWA
function initPwaInstallWidget() {
    const deviceType = detectDevice();
    console.log('Обнаружено устройство:', deviceType);
    
    const widget = document.getElementById('pwa-install-widget');
    if (!widget) return;
    
    // Показываем виджет только для поддерживаемых устройств и если приложение не установлено
    if (window.matchMedia('(display-mode: standalone)').matches) {
        return; // Приложение уже установлено
    }
    
    // Обработка события beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Показываем виджет для пользователя
        widget.classList.add('show');
        
        // Настраиваем виджет в зависимости от устройства
        const deviceIcon = widget.querySelector('.device-icon');
        const instructions = widget.querySelector('.pwa-install-instructions');
        
        if (deviceIcon) {
            deviceIcon.className = 'device-icon ' + deviceType;
        }
        
        // Закрытие виджета
        const closeBtn = widget.querySelector('.close-pwa-widget');
        if (closeBtn) {
            closeBtn.addEventListener('touchstart', (e) => {
                e.preventDefault();
                widget.classList.remove('show');
                
                // Запоминаем, что пользователь закрыл виджет
                localStorage.setItem('pwa-install-dismissed', new Date().toString());
            });
            
            closeBtn.addEventListener('touchmove', (e) => {
                e.preventDefault();
            });
        }
        
        // Установка приложения
        const installBtn = widget.querySelector('.install-pwa-btn');
        if (installBtn && deferredPrompt) {
            installBtn.addEventListener('click', async () => {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                
                if (outcome === 'accepted') {
                    console.log('Пользователь принял установку PWA');
                    widget.classList.remove('show');
                    
                    // Отправляем аналитику установки
                    fetch('/pwa/track-install', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ device: deviceType })
                    });
                } else {
                    console.log('Пользователь отклонил установку PWA');
                }
                
                deferredPrompt = null;
            });
        }
    });
    
    // Проверяем, не отклонял ли пользователь установку ранее
    const dismissed = localStorage.getItem('pwa-install-dismissed');
    if (dismissed) {
        const dismissedDate = new Date(dismissed);
        const now = new Date();
        
        // Показываем снова через 7 дней
        if ((now - dismissedDate) < 7 * 24 * 60 * 60 * 1000) {
            return;
        }
    }
    
    // Регистрация Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => {
                    console.log('Service Worker зарегистрирован:', registration);
                })
                .catch(error => {
                    console.error('Ошибка регистрации Service Worker:', error);
                });
        });
    }
}

// Инициализируем PWA при загрузке документа
document.addEventListener('DOMContentLoaded', initPwaInstallWidget);
