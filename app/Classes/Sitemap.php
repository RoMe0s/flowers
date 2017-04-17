<?php

namespace App\Classes;

use App\Models\Page;
use App\Models\Set;
use App\Models\Bouquet;
use App\Models\Category;
use App\Models\Product;
use File;

class Sitemap {

	private $data;

	private $market_view = 'yml';

	private $sitemap_view = 'xml';

	function __construct() {
		$this->data = [
			'sets' => Set::whereHas('box', function($box_query) {
				return $box_query->whereHas('category', function($category_query) {
					return $category_query->where('status', true);
				});
			})
			->with('box', 'box.category', 'translations')->get(),
            'bouquets' => Bouquet::has('category')->with('translations', 'category')->get(),
            'categories' => Category::with('translations')->get(),
			'presents' => Product::has('category')->with(['translations', 'category'])->get()
		];
	}

	public function getMarketView() {
		return view($this->market_view, $this->data);
	}

	public function getSitemapView() {

        $pages = Page::where('slug', 'NOT LIKE', '%order%')
            ->select(
                'slug',
                \DB::raw('IFNULL(updated_at, created_at) as date')
            )
            ->where('slug', 'NOT LIKE', '%password-reset-with-token%')
            ->visible()
            ->get()
            ->keyBy('slug');

        $this->data['pages'] = $pages;

        $this->data['priority_groups'] = $this->_getPriorityGroups();

		return view($this->sitemap_view, $this->data);
	}

	public function saveFile() {
		
		$_path = public_path('sitemap.xml');
		
		File::delete($_path);

		File::put($_path, $this->getSitemapView());
	
	}

    private function _getPriorityGroups() {

	    return [
            '1.0' => [
                [
                    'url' => route('home'),
                    'freq' => 'always'
                ]
            ],
            '0.8' => [
                [
                    'url' => route('shares'),
                    'freq' => 'always'
                ],
                [
                    'url' => route('subscriptions'),
                    'freq' => 'always'
                ],
                [
                    'url' => route('flowers'),
                    'freq' => 'always'
                ],
                [
                    'url' => route('presents'),
                    'freq' => 'always'
                ],
                [
                    'url' => route('individuals'),
                    'freq' => 'weekly'
                ]
            ],
            '0.5' => [
                [
                    'url' => route('news'),
                    'freq' => 'daily'
                ],
                [
                    'url' => route('login'),
                    'freq' => 'weekly'
                ],
                [
                    'url' => route('reg'),
                    'freq' => 'weekly'
                ],
                [
                    'url' => route('password.reset'),
                    'freq' => 'weekly'
                ]
            ],
            'page' => [
                'freq' => 'weekly',
                'priority' => '0.5'
            ],
            'product' => [
                'freq' => 'always',
                'priority' => '1.0'
            ],
            'category' => [
                'freq' => 'daily',
                'priority' => '0.8'
            ]
        ];

    }


}
