<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($priority_groups as $priority => $priority_group)
	@foreach($priority_group as $item)
		@if(is_array($item))
			<?php
				$exploded_slug = explode('/', str_replace(url(''), '', $item['url']));
				$slug = array_pop($exploded_slug);
				$slug = empty($slug) ? 'home' : $slug;
				$page = $pages[$slug];
			?>
			<url>
				<loc>
					{{ $item['url'] }}
				</loc>
				<lastmod>
					{!! isset($page->date) ? $page->date : \Carbon\Carbon::now()->startOfDay() !!}
				</lastmod>
				<priority>
					{{ $priority }}
				</priority>
				<changefreq>
					{{ $item['freq'] }}
				</changefreq>
			</url>
			<?php
				unset($pages[$slug]);
			?>
		@endif
	@endforeach
@endforeach
@foreach($pages as $page)
		<url>
			<loc>
				{{ $page->getUrl() }}
			</loc>
			<lastmod>
				{!! isset($page->date) ? $page->date: \Carbon\Carbon::now()->startOfDay() !!}
			</lastmod>
			<priority>
				{!! $priority_groups['page']['priority'] !!}				
			</priority>
			<changefreq>
				{!! $priority_groups['page']['freq']  !!}
			</changefreq>
		</url>
@endforeach
@foreach($categories as $category)
	<url>
		<loc>
			{!! $category->getUrl() !!}
		</loc>
		<lastmod>
			{!! \Carbon\Carbon::now()->startOfDay() !!}
		</lastmod>
		<priority>
			{!! $priority_groups['category']['priority'] !!}
		</priority>
		<changefreq>
			{!! $priority_groups['category']['freq']  !!}
		</changefreq>
	</url>
@endforeach
	@foreach($sets as $product)
		<url>
			<loc>
				{!! $product->getUrl() !!}
			</loc>
			<lastmod>
				{!! isset($product->updated_at) ? $product->updated_at : (isset($product->created_at) ? $product->created_at : \Carbon\Carbon::now()->startOfDay()) !!}
			</lastmod>
			<priority>
				{!! $priority_groups['product']['priority'] !!}
			</priority>
			<changefreq>
				{!! $priority_groups['product']['freq']  !!}
			</changefreq>
		</url>
	@endforeach
	@foreach($bouquets as $product)
		<url>
			<loc>
				{!! $product->getUrl() !!}
			</loc>
			<lastmod>
				{!! isset($product->updated_at) ? $product->updated_at : (isset($product->created_at) ? $product->created_at : \Carbon\Carbon::now()->startOfDay()) !!}
			</lastmod>
			<priority>
				{!! $priority_groups['product']['priority'] !!}
			</priority>
			<changefreq>
				{!! $priority_groups['product']['freq']  !!}
			</changefreq>
		</url>
	@endforeach
	@foreach($presents as $product)
		<url>
			<loc>
				{!! $product->getUrl() !!}
			</loc>
			<lastmod>
				{!! isset($product->updated_at) ? $product->updated_at : (isset($product->created_at) ? $product->created_at : \Carbon\Carbon::now()->startOfDay()) !!}
			</lastmod>
			<priority>
				{!! $priority_groups['product']['priority'] !!}
			</priority>
			<changefreq>
				{!! $priority_groups['product']['freq']  !!}
			</changefreq>
		</url>
	@endforeach
</urlset>