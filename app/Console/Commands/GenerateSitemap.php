<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\NewsPost;
use App\Models\Program;
use App\Models\Project;
use App\Models\SuccessStory;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';

    protected $description = 'Generate the public sitemap.xml from published content';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        foreach ([
            ['/', 1.0],
            ['/about', 0.8],
            ['/programs', 0.9],
            ['/projects', 0.8],
            ['/events', 0.7],
            ['/blog', 0.7],
            ['/news', 0.6],
            ['/gallery', 0.5],
            ['/faqs', 0.5],
            ['/resources', 0.5],
            ['/contact', 0.6],
            ['/campaigns', 0.8],
            ['/donate', 0.9],
            ['/success-stories', 0.7],
        ] as [$path, $priority]) {
            $sitemap->add(Url::create($path)->setPriority($priority));
        }

        $this->addModelUrls($sitemap, Program::published()->get(), fn ($m) => "/programs/{$m->slug}");
        $this->addModelUrls($sitemap, Project::published()->get(), fn ($m) => "/projects/{$m->slug}");
        $this->addModelUrls($sitemap, Event::published()->get(), fn ($m) => "/events/{$m->slug}");
        $this->addModelUrls($sitemap, BlogPost::published()->get(), fn ($m) => "/blog/{$m->slug}");
        $this->addModelUrls($sitemap, NewsPost::published()->get(), fn ($m) => "/news/{$m->slug}");
        $this->addModelUrls($sitemap, GalleryAlbum::published()->get(), fn ($m) => "/gallery/{$m->slug}");
        $this->addModelUrls($sitemap, Campaign::published()->get(), fn ($m) => "/campaigns/{$m->slug}");
        $this->addModelUrls($sitemap, SuccessStory::published()->get(), fn ($m) => "/success-stories/{$m->slug}");

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml with '.count($sitemap->getTags()).' URLs.');
    }

    private function addModelUrls(Sitemap $sitemap, iterable $models, \Closure $pathResolver): void
    {
        foreach ($models as $model) {
            $sitemap->add(
                Url::create($pathResolver($model))
                    ->setLastModificationDate($model->updated_at)
                    ->setPriority(0.6)
            );
        }
    }
}
