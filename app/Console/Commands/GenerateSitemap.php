<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--details : Показывать подробную информацию о процессе}';
    protected $description = 'Генерация файлов sitemap по стандартам Яндекса для улучшения индексации';

    public function handle()
    {
        $this->info('Начало генерации Sitemap...');
        
        // Устанавливаем принудительный HTTPS для URL-адресов, если сайт работает через HTTPS
        URL::forceScheme('https');
        
        // Генерируем все типы sitemap
        $this->generateMainSitemap();
        $this->generateRecipesSitemap();
        $this->generateCategoriesSitemap();
        $this->generateStaticSitemap();
        $this->generatePaginationSitemap();
        $this->generateUsersSitemap();
        
        $this->info('Генерация Sitemap успешно завершена!');
        
        // Выводим информацию о количестве URL в каждом файле
        if ($this->option('details')) {
            $this->displaySitemapStats();
        }
        
        return 0;
    }
    
    /**
     * Генерирует основной файл sitemap.xml
     */
    private function generateMainSitemap()
    {
        $this->info('Генерация основного sitemap.xml...');
        
        $now = Carbon::now()->toAtomString();
        
        // Создаем список всех sitemap-файлов для индекса
        $sitemaps = [
            [
                'loc' => url('sitemap-recipes.xml'),
                'lastmod' => $now
            ],
            [
                'loc' => url('sitemap-categories.xml'),
                'lastmod' => $now
            ],
            [
                'loc' => url('sitemap-static.xml'),
                'lastmod' => $now
            ],
            [
                'loc' => url('sitemap-pagination.xml'),
                'lastmod' => $now
            ],
            [
                'loc' => url('sitemap-users.xml'),
                'lastmod' => $now
            ]
        ];
        
        $content = view('sitemaps.index', compact('sitemaps'))->render();
        
        File::put(public_path('sitemap.xml'), $content);
        
        $this->info('Основной sitemap.xml создан успешно.');
    }
    
    /**
     * Генерирует sitemap для рецептов
     */
    private function generateRecipesSitemap()
    {
        $this->info('Генерация sitemap-recipes.xml...');
        
        $recipes = Recipe::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $urls = $recipes->map(function($recipe) {
            return [
                'loc' => route('recipes.show', $recipe->slug),
                'lastmod' => $recipe->updated_at->toAtomString(),
                'changefreq' => $this->getChangeFrequency($recipe->updated_at),
                'priority' => '0.8',
                'image' => $recipe->image_url
            ];
        });
        
        $content = view('sitemaps.urls', compact('urls'))->render();
        File::put(public_path('sitemap-recipes.xml'), $content);
        
        $this->info('sitemap-recipes.xml создан успешно: ' . $urls->count() . ' URL.');
    }
    
    /**
     * Генерирует sitemap для категорий
     */
    private function generateCategoriesSitemap()
    {
        $this->info('Генерация sitemap-categories.xml...');
        
        $categories = Category::orderBy('updated_at', 'desc')->get();
            
        $urls = $categories->map(function($category) {
            return [
                'loc' => route('categories.show', $category->slug),
                'lastmod' => $category->updated_at ? $category->updated_at->toAtomString() : Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
                'image' => $category->image ?? null
            ];
        });
        
        $content = view('sitemaps.urls', compact('urls'))->render();
        File::put(public_path('sitemap-categories.xml'), $content);
        
        $this->info('sitemap-categories.xml создан успешно: ' . $urls->count() . ' URL.');
    }
    
    /**
     * Генерирует sitemap для статических страниц
     */
    private function generateStaticSitemap()
    {
        $this->info('Генерация sitemap-static.xml...');
        
        $urls = [
            [
                'loc' => route('home'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => route('recipes.index'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => route('categories.index'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => route('search'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'loc' => route('legal.terms'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            [
                'loc' => route('legal.privacy'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            [
                'loc' => route('legal.disclaimer'),
                'lastmod' => Carbon::now()->toAtomString(), 
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            [
                'loc' => route('legal.dmca'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            [
                'loc' => route('pwa.install-info'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ],
        ];
        
        $content = view('sitemaps.urls', compact('urls'))->render();
        File::put(public_path('sitemap-static.xml'), $content);
        
        $this->info('sitemap-static.xml создан успешно: ' . count($urls) . ' URL.');
    }
    
    /**
     * Генерирует sitemap для страниц пагинации
     */
    private function generatePaginationSitemap()
    {
        $this->info('Генерация sitemap-pagination.xml...');
        
        $urls = [];
        
        // Пагинация для списка рецептов
        $recipeCount = Recipe::where('is_published', true)->count();
        $recipesPerPage = 12;
        $totalRecipePages = ceil($recipeCount / $recipesPerPage);
        
        for ($page = 2; $page <= $totalRecipePages; $page++) {
            $urls[] = [
                'loc' => route('recipes.index', ['page' => $page]),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.6'
            ];
        }
        
        // Пагинация для категорий
        $categories = Category::all();
        foreach ($categories as $category) {
            $categoryRecipeCount = $category->recipes()->where('is_published', true)->count();
            $totalCategoryPages = ceil($categoryRecipeCount / $recipesPerPage);
            
            for ($page = 2; $page <= $totalCategoryPages; $page++) {
                $urls[] = [
                    'loc' => route('categories.show', ['slug' => $category->slug, 'page' => $page]),
                    'lastmod' => Carbon::now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5'
                ];
            }
        }
        
        $content = view('sitemaps.urls', compact('urls'))->render();
        File::put(public_path('sitemap-pagination.xml'), $content);
        
        $this->info('sitemap-pagination.xml создан успешно: ' . count($urls) . ' URL.');
    }
    
    /**
     * Генерирует sitemap для страниц пользователей
     */
    private function generateUsersSitemap()
    {
        $this->info('Генерация sitemap-users.xml...');
        
        $users = User::has('recipes')->get();
        
        $urls = $users->map(function($user) {
            return [
                'loc' => route('user.profile', $user->id),
                'lastmod' => $user->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
        });
        
        $content = view('sitemaps.urls', compact('urls'))->render();
        File::put(public_path('sitemap-users.xml'), $content);
        
        $this->info('sitemap-users.xml создан успешно: ' . $urls->count() . ' URL.');
    }
    
    /**
     * Определяет частоту изменения контента на основе даты последнего обновления
     */
    private function getChangeFrequency($updatedAt)
    {
        $daysSinceUpdate = Carbon::now()->diffInDays($updatedAt);
        
        if ($daysSinceUpdate < 7) {
            return 'daily';
        } elseif ($daysSinceUpdate < 30) {
            return 'weekly';
        } else {
            return 'monthly';
        }
    }
    
    /**
     * Отображает статистику файлов sitemap
     */
    private function displaySitemapStats()
    {
        $files = [
            'sitemap.xml',
            'sitemap-recipes.xml',
            'sitemap-categories.xml',
            'sitemap-static.xml',
            'sitemap-pagination.xml',
            'sitemap-users.xml'
        ];
        
        $this->info('Статистика по файлам sitemap:');
        $this->table(
            ['Файл', 'Размер', 'Последнее обновление'],
            collect($files)->map(function($file) {
                $path = public_path($file);
                if (File::exists($path)) {
                    $size = File::size($path);
                    $formattedSize = $size > 1024 
                        ? round($size / 1024, 2) . ' KB' 
                        : $size . ' bytes';
                    $lastModified = Carbon::createFromTimestamp(File::lastModified($path))->format('Y-m-d H:i:s');
                    return [$file, $formattedSize, $lastModified];
                }
                return [$file, 'Не существует', '-'];
            })
        );
        
        // Проверка доступности файлов
        $this->info('Проверка доступности файлов sitemap:');
        $this->info('- Основной sitemap: ' . url('/sitemap.xml'));
        $this->info('- Для рецептов: ' . url('/sitemap-recipes.xml'));
        $this->info('- Для категорий: ' . url('/sitemap-categories.xml'));
        $this->info('- Для статических страниц: ' . url('/sitemap-static.xml'));
        $this->info('- Для пагинации: ' . url('/sitemap-pagination.xml'));
        $this->info('- Для пользователей: ' . url('/sitemap-users.xml'));
    }
}
