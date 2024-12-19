<?php

namespace App\Console\Commands;

use App\Repositories\ArticleRepository;
use App\Services\NewsAggregationService;
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

    private NewsAggregationService $newsAggregationService;
    private array $newsSources;

    /**
     * Execute the console command.
     */

    public function __construct(NewsAggregationService $newsAggregationService)
    {
        parent::__construct();
        $this->newsAggregationService = $newsAggregationService;
    }
    public function handle(): int
    {
        $this->info('Starting news aggregation process...');
        $this->newsAggregationService->fetchAndStoreNews();
        $this->info('News aggregation completed.');
        return Command::SUCCESS;
    }
}
