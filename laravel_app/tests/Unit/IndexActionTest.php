<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexActionTest extends TestCase
{
    /**
     * To check being redirected to /login, when being accessed /
     *
     * @return void
     */
    public function testRedirect()
    {
        $this->get('http://localhost:8080/')
             ->assertRedirect('/login');
    }
}
