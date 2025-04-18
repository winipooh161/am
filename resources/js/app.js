import './bootstrap';
import * as bootstrap from 'bootstrap';
import $ from 'jquery';

// Глобальный доступ к bootstrap и jQuery
window.bootstrap = bootstrap;
window.$ = window.jQuery = $;

// Импортируем стили
import '../css/app.css';

// Функция для анимации элементов при скролле
function animateOnScroll() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    const windowHeight = window.innerHeight;
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        
        if (elementTop < windowHeight - 100) {
            element.classList.add('animated');
        }
    });
}

// Функция для переключения темной темы
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
    
    // Обновляем иконку переключателя
    const darkModeIcon = document.getElementById('dark-mode-icon');
    if (darkModeIcon) {
        darkModeIcon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    // Обновляем текст переключателя
    const darkModeText = document.getElementById('dark-mode-text');
    if (darkModeText) {
        darkModeText.textContent = isDarkMode ? 'Светлая тема' : 'Темная тема';
    }
}

// Плавная прокрутка к элементам на странице
function enableSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Анимация цифр (например, для статистики)
function animateNumbers() {
    const numberElements = document.querySelectorAll('.animate-number');
    
    numberElements.forEach(element => {
        const targetNumber = parseInt(element.getAttribute('data-target'));
        const duration = 1500; // ms
        const step = targetNumber / (duration / 16); // 60fps
        
        let current = 0;
        const timer = setInterval(() => {
            current += step;
            element.textContent = Math.floor(current);
            
            if (current >= targetNumber) {
                element.textContent = targetNumber;
                clearInterval(timer);
            }
        }, 16);
    });
}

// Анимация для списка шагов приготовления
function animateSteps() {
    const steps = document.querySelectorAll('.step-item');
    
    steps.forEach((step, index) => {
        step.style.animationDelay = `${index * 0.1}s`;
    });
}

// Интерактивная форма поиска
function enhanceSearchForm() {
    const searchInput = document.querySelector('input[name="query"]');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('search-active');
        });
        
        searchInput.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('search-active');
            }
        });
    }
}

// Добавление класса "animate-on-scroll" к элементам, которые нужно анимировать
function setupScrollAnimations() {
    const elementsToAnimate = [
        '.card', 
        '.recipe-image-container', 
        '.recipe-description', 
        '.step-item', 
        '.home-banner',
        'h1',
        'h2',
        '.category-card'
    ];
    
    elementsToAnimate.forEach(selector => {
        document.querySelectorAll(selector).forEach(element => {
            if (!element.classList.contains('animate-on-scroll')) {
                element.classList.add('animate-on-scroll');
            }
        });
    });
}

// Инициализация всех функций при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация всплывающих подсказок
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Автоматическое закрытие уведомлений
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const alertInstance = new bootstrap.Alert(alert);
            alertInstance.close();
        }, 5000);
    });
    
    // Проверка сохраненной темы
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        
        // Обновляем иконку переключателя
        const darkModeIcon = document.getElementById('dark-mode-icon');
        if (darkModeIcon) {
            darkModeIcon.className = 'fas fa-sun';
        }
        
        // Обновляем текст переключателя
        const darkModeText = document.getElementById('dark-mode-text');
        if (darkModeText) {
            darkModeText.textContent = 'Светлая тема';
        }
    }
    
    // Инициализация кнопки переключения темы
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
    
    // Настройка анимаций при скролле
    setupScrollAnimations();
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
    
    // Включаем плавную прокрутку
    enableSmoothScroll();
    
    // Анимируем шаги приготовления
    animateSteps();
    
    // Улучшаем форму поиска
    enhanceSearchForm();
    
    // Анимация чисел, если они есть на странице
    animateNumbers();
    
    // Обработка загрузки изображений
    const lazyImages = document.querySelectorAll('.recipe-image:not(.loaded)');
    lazyImages.forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
            this.parentElement.classList.add('image-loaded');
        });
    });
    
    // Инициализация превью изображений при загрузке
    const imageInputs = document.querySelectorAll('input[type="file"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const previewContainer = document.createElement('div');
                previewContainer.className = 'image-preview mt-2';
                
                reader.onload = function(e) {
                    previewContainer.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">`;
                }
                
                reader.readAsDataURL(this.files[0]);
                
                // Удаляем предыдущий превью, если он существует
                const existingPreview = this.parentElement.querySelector('.image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                this.parentElement.appendChild(previewContainer);
            }
        });
    });
    
    // Глобальный обработчик ошибок для изображений рецептов
    document.querySelectorAll('img.recipe-img, img.card-img-top').forEach(img => {
        if (!img.hasAttribute('onerror')) {
            img.onerror = function() {
                if (!this.src.includes('placeholder.jpg')) {
                    this.src = '/images/placeholder.jpg';
                }
                this.onerror = null; // Предотвращаем бесконечную рекурсию
            };
        }
    });
    
    // Инициализация обработки изображений
    initializeImageHandling();
    
    // Инициализация анимаций при прокрутке
    initializeScrollAnimations();
    
    // Инициализация калькулятора порций
    initializeServingsCalculator();
});

// Функция для обработки изображений
function initializeImageHandling() {
    // Обработка ошибок загрузки изображений
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function(e) {
            if (!this.parentElement) return;
            
            // Добавляем класс для элементов с ошибкой загрузки
            this.parentElement.classList.add('image-error');
            
            // Создаем заместитель для изображения
            const placeholder = document.createElement('div');
            placeholder.className = 'image-placeholder';
            placeholder.innerHTML = '<i class="fa fa-image"></i><span>Изображение недоступно</span>';
            
            // Заменяем изображение на заместитель
            this.parentElement.appendChild(placeholder);
        });
    });
}

// Функция для анимаций при прокрутке
function initializeScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-element');
    
    function checkIfInView() {
        const windowHeight = window.innerHeight;
        const windowTopPosition = window.scrollY;
        const windowBottomPosition = windowTopPosition + windowHeight;
        
        animatedElements.forEach(element => {
            const elementHeight = element.offsetHeight;
            const elementTopPosition = element.getBoundingClientRect().top + windowTopPosition;
            const elementBottomPosition = elementTopPosition + elementHeight;
            
            // Проверка, находится ли элемент в области видимости
            if ((elementBottomPosition >= windowTopPosition) && 
                (elementTopPosition <= windowBottomPosition)) {
                element.classList.add('animated');
            }
        });
    }
    
    // Проверяем при прокрутке и при загрузке
    window.addEventListener('scroll', checkIfInView);
    window.addEventListener('load', checkIfInView);
}

// Функция для калькулятора порций
function initializeServingsCalculator() {
    const servingsInput = document.getElementById('servings-input');
    const decreaseBtn = document.getElementById('decrease-servings');
    const increaseBtn = document.getElementById('increase-servings');
    
    if (!servingsInput || !decreaseBtn || !increaseBtn) return;
    
    const ingredients = document.querySelectorAll('.ingredient-item');
    const originalQuantities = [];
    
    // Сохраняем исходные количества ингредиентов
    ingredients.forEach(ingredient => {
        const quantityElement = ingredient.querySelector('.ingredient-quantity');
        if (quantityElement) {
            originalQuantities.push(parseFloat(quantityElement.getAttribute('data-quantity') || '0'));
        } else {
            originalQuantities.push(0);
        }
    });
    
    // Обработчик изменения порций
    function updateServings() {
        const newServings = parseInt(servingsInput.value) || 1;
        const originalServings = parseInt(servingsInput.getAttribute('data-original') || '1');
        const ratio = newServings / originalServings;
        
        // Обновляем количества ингредиентов
        ingredients.forEach((ingredient, index) => {
            const quantityElement = ingredient.querySelector('.ingredient-quantity');
            if (quantityElement && originalQuantities[index]) {
                const newQuantity = (originalQuantities[index] * ratio).toFixed(1).replace(/\.0$/, '');
                quantityElement.textContent = newQuantity;
            }
        });
    }
    
    // Добавляем обработчики событий
    decreaseBtn.addEventListener('click', () => {
        const currentValue = parseInt(servingsInput.value) || 1;
        if (currentValue > 1) {
            servingsInput.value = currentValue - 1;
            updateServings();
        }
    });
    
    increaseBtn.addEventListener('click', () => {
        const currentValue = parseInt(servingsInput.value) || 1;
        servingsInput.value = currentValue + 1;
        updateServings();
    });
    
    servingsInput.addEventListener('change', updateServings);
}
