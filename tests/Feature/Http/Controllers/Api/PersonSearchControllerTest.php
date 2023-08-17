<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonSearchControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider partialTermDataProvider
     */
    public function it_behaves_as_expected_with_nickname(bool $partialTerm): void
    {
        $people = Person::factory()->count(5)->create();

        $term = $partialTerm
            ? substr($people->first()->nickname, 0, -1)
            : $people->first()->nickname;

        $response = $this->getJson('/pessoas?t=' . $term);

        $response->assertSuccessful();
        $this->assertGreaterThanOrEqual(1, count($response->json()));
    }

    /**
     * @test
     * @dataProvider partialTermDataProvider
     */
    public function it_behaves_as_expected_with_stack(bool $partialTerm): void
    {
        $people = Person::factory()->count(5)->create();

        $term = $partialTerm
            ? substr($people->first()->stack[0], 0, -1)
            : $people->first()->stack[0];

        $response = $this->getJson('/pessoas?t=' . $term);

        $response->assertSuccessful();
        $this->assertGreaterThanOrEqual(1, count($response->json()));
    }

    /**
     * @test
     * @dataProvider partialTermDataProvider
     */
    public function it_behaves_as_expected_with_name(bool $partialTerm): void
    {
        $people = Person::factory()->count(5)->create();

        $term = $partialTerm
            ? substr($people->first()->name, 0, -1)
            : $people->first()->name;

        $response = $this->getJson('/pessoas?t=' . $term);

        $response->assertSuccessful();
        $this->assertGreaterThanOrEqual(1, count($response->json()));
    }

    /** @test */
    public function it_behaves_as_expected_with_invalid_search_term(): void
    {
        Person::factory()->count(5)->create();

        $response = $this->getJson('/pessoas?t=');

        $response->assertBadRequest();
    }

    /** @test */
    public function it_behaves_as_expected_with_inexistent_term(): void
    {
        Person::factory()->count(5)->create();

        $term = 'inexistent';

        $response = $this->getJson('/pessoas?t=' . $term);

        $response->assertSuccessful();
        $response->assertJsonCount(0);
    }

    public static function partialTermDataProvider()
    {
        return [
            [
                true
            ],
            [
                false
            ],
        ];
    }
}
