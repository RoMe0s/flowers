<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($priority_groups as $priority => $priority_group)
	@foreach($priority_group as $item)
		@if(is_array($item))
			<?php try {
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
					{!! isset($page->date) ? \Carbon\Carbon::parse($page->date)->toW3cString() : \Carbon\Carbon::now()->startOfDay()->toW3cString() !!}
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
				} catch (Exception $e) { continue; }
			?>
		@endif
	@endforeach
@endforeach
@foreach($pages as $page)
            <?php try { ?>
		<url>
			<loc>
				{{ $page->getUrl() }}
			</loc>
			<lastmod>
				{!! isset($page->date) ? \Carbon\Carbon::parse($page->date)->toW3cString(): \Carbon\Carbon::now()->startOfDay()->toW3cString() !!}
			</lastmod>
			<priority>
				{!! $priority_groups['page']['priority'] !!}				
			</priority>
			<changefreq>
				{!! $priority_groups['page']['freq']  !!}
			</changefreq>
		</url>
        <?php
        } catch (Exception $e) { continue; }
        ?>
@endforeach
@foreach($categories as $category)
        <?php try { ?>
	<url>
		<loc>
			{!! $category->getUrl() !!}
		</loc>
		<lastmod>
			{!! \Carbon\Carbon::now()->startOfDay()->toW3cString() !!}
		</lastmod>
		<priority>
			{!! $priority_groups['category']['priority'] !!}
		</priority>
		<changefreq>
			{!! $priority_groups['category']['freq']  !!}
		</changefreq>
	</url>
            <?php
            } catch (Exception $e) { continue; }
            ?>
@endforeach
	@foreach($sets as $product)
        <?php try { ?>
		<url>
			<loc>
				{!! $product->getUrl() !!}
			</loc>
			<lastmod>
				{!! isset($product->updated_at) ? \Carbon\Carbon::parse($product->updated_at)->toW3cString() : (isset($product->created_at) ? \Carbon\Carbon::parse($product->created_at)->toW3cString() : \Carbon\Carbon::now()->startOfDay()->toW3cString()) !!}
			</lastmod>
			<priority>
				{!! $priority_groups['product']['priority'] !!}
			</priority>
			<changefreq>
				{!! $priority_groups['product']['freq']  !!}
			</changefreq>
		</url>
        <?php
        } catch (Exception $e) { continue; }
        ?>
	@endforeach
	@foreach($bouquets as $product)
        <?php try { ?>
		<url>
			<loc>
				{!! $product->getUrl() !!}
			</loc>
			<lastmod>
				{!! isset($product->updated_at) ? \Carbon\Carbon::parse($product->updated_at)->toW3cString() : (isset($product->created_at) ? \Carbon\Carbon::parse($product->created_at)->toW3cString() : \Carbon\Carbon::now()->startOfDay()->toW3cString()) !!}
			</lastmod>
			<priority>
				{!! $priority_groups['product']['priority'] !!}
			</priority>
			<changefreq>
				{!! $priority_groups['product']['freq']  !!}
			</changefreq>
		</url>
            <?php
            } catch (Exception $e) { continue; }
            ?>
	@endforeach
	@foreach($presents as $product)
        <?php try { ?>
		<url>
			<loc>
				{!! $product->getUrl() !!}
			</loc>
			<lastmod>
				{!! isset($product->updated_at) ? \Carbon\Carbon::parse($product->updated_at)->toW3cString() : (isset($product->created_at) ? \Carbon\Carbon::parse($product->created_at)->toW3cString() : \Carbon\Carbon::now()->startOfDay()->toW3cString()) !!}
			</lastmod>
			<priority>
				{!! $priority_groups['product']['priority'] !!}
			</priority>
			<changefreq>
				{!! $priority_groups['product']['freq']  !!}
			</changefreq>
		</url>
        <?php
        } catch (Exception $e) { continue; }
        ?>
	@endforeach
</urlset>