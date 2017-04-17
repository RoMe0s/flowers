<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\Sitemap;
class SitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $sitemap = new Sitemap();

	    $sitemap->saveFile();			
    		
	    $this->info('Sitemap generated');	    
    
    }
}
