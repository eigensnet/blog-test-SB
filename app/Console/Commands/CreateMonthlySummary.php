<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

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
        $adminId = 1;
        $title = 'summary ' . now()->format('m.Y');
        $body = 'content:';
        $categoryId = 1;

        Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $adminId,
            'category_id'=> $categoryId
        ]);

        $this->info('Monthly summary post created successfully.');
    }

}
