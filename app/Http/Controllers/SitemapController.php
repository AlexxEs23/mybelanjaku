<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Produk;
use App\Models\Kategori;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();
        
        // Homepage - Highest priority
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );
        
        // All active products - High priority
        Produk::where('status', true)
            ->with('kategori')
            ->get()
            ->each(function (Produk $produk) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('produk.detail', $produk->slug))
                        ->setLastModificationDate($produk->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            });
        
        // Static pages - Medium priority
        $staticPages = [
            ['route' => 'login', 'priority' => 0.3],
            ['route' => 'register', 'priority' => 0.3],
        ];
        
        foreach ($staticPages as $page) {
            try {
                $sitemap->add(
                    Url::create(route($page['route']))
                        ->setLastModificationDate(Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority($page['priority'])
                );
            } catch (\Exception $e) {
                // Skip if route doesn't exist
                continue;
            }
        }
        
        return $sitemap->toResponse(request());
    }
}
