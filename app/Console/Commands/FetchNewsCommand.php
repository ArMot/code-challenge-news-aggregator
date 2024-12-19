<?php

namespace App\Console\Commands;

use App\Repositories\ArticleRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private ArticleRepository $articleRepository;
    private array $newsSources;

    /**
     * Execute the console command.
     */

    public function __construct(ArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
        $this->newsSources = app('newsSources');
    }
    public function handle(): int
    {
        foreach ($this->newsSources as $sourceName => $sourceService) {
            $this->info("Fetching articles from: $sourceName");

            try {
                $articles = $sourceService->fetchArticles();

                $this->storeArticles($articles);
                foreach ($articles as $article) {
                    $this->articleRepository->store($article);
                }

                $this->info("Successfully fetched and stored articles from $sourceName.");
            } catch (\Exception $e) {
                Log::error("Error fetching articles from $sourceName: {$e->getMessage()}");
                $this->error("Failed to fetch articles from $sourceName. Check logs for details.");
            }
        }

        return Command::SUCCESS;
    }

    /**
    * @param Article[] $articles
    */
    private function storeArticles(array $articles): void
    {
        foreach ($articles as $article) {
            try {
                $this->articleRepository->store($article);
            } catch (\Exception $e) {
                Log::error("could not save article with url {$article->url}: {$e->getMessage()}");
                $this->error("could not save article with url {$article->url}: {$e->getMessage()}");
            }
        }
    }
}
