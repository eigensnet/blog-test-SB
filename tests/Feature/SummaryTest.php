<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SummaryTest extends TestCase
{

    use RefreshDatabase;
    public function testPostcreate()
    {
        $adminId = 1;
        $this->artisan('post:create-monthly-summary')
            ->assertExitCode(0);

        $this->assertDatabaseHas('posts', [
            'title' => 'summary ' . now()->format('m.Y'),
            'user_id' => $adminId,
        ]);
    }

}
