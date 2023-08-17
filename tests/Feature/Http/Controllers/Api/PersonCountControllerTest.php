<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonCountControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_behaves_as_expected(): void
    {
        $people = Person::factory()->count(3)->create();

        $response = $this->getJson('/contagem-pessoas');

        $response->assertSuccessful();
        $response->assertSeeText(count($people));
    }
}
