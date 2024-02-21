<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use App\Models\Category;

class CreateMonthlySummary extends Command
{
    protected $signature = 'post:create-monthly-summary';
    protected $description = 'monthly post summary';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // \Log::info('started posting.');
        $adminId = 1;
        $title = 'Zusammenfassung ' . now()->format('m.Y');
        $body = 'content:';
        $searchedId = Category::firstOrCreate(['name' => 'summary_report']);
        $categoryId = $searchedId->id;

        Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $adminId,
            'category_id'=> $categoryId,
            'is_published' => 0,
        ]);

        $this->info('Monthly summary post created successfully.');
        // \Log::info('ending posting.');
    }

}
