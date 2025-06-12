<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiVersionTest extends TestCase
{
    public function testV1RouteReturnsSuccessResponse(): void
    {
        $this->get('api/v1/test')
        ->assertOk()
        ->assertJsonStructure(['message']);
    }
}
