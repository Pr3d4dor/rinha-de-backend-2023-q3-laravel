<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function show_behaves_as_expected(): void
    {
        $person = Person::factory()->create();

        $personCacheData = json_encode($person->toArray());

        Cache::set('person.' . $person['uuid'], $personCacheData, now()->addDay());

        $response = $this->getJson('/pessoas/' . $person['uuid']);

        $response->assertSuccessful();
        $response->assertJson([
            'id' => $person['uuid'],
            'apelido' => $person['nickname'],
            'nome' => $person['name'],
            'nascimento' => $person['date_of_birth']->format('Y-m-d'),
            'stack' => $person['stack'],
        ]);
    }

    /** @test */
    public function show_behaves_as_expected_with_inexistent_person(): void
    {
        $person = Person::factory()->create();

        $response = $this->getJson('/pessoas/' . 'invalid');

        $response->assertNotFound();
    }

    /** @test */
    public function store_behaves_as_expected(): void
    {
        $data = [
            'apelido' => $this->faker->userName(),
            'nome' => $this->faker->name(),
            'nascimento' => $this->faker->date('Y-m-d'),
            'stack' => [
                $this->faker->word(),
                $this->faker->word(),
            ],
        ];

        $response = $this->postJson('/pessoas', $data);

        $response->assertCreated();
        $response->assertJson($data);
        $response->assertHeader('Location', sprintf('/pessoas/' . $response->json('id')));
        $this->assertTrue(Cache::has('person.' . $response->json('id')));
    }

    /**
     * @test
     * @dataProvider storeInvalidDataDataProvider
     */
    public function store_behaves_as_expected_with_invalid_data(array $data, int $statusCode): void
    {
        $response = $this->postJson('/pessoas', $data);

        $response->assertStatus($statusCode);
        $this->assertDatabaseEmpty('people');
    }

    /** @test */
    public function store_behaves_as_expected_when_nickname_is_already_taken()
    {
        $data = [
            'apelido' => $this->faker->userName(),
            'nome' => $this->faker->name(),
            'nascimento' => $this->faker->date('Y-m-d'),
            'stack' => [
                $this->faker->word(),
                $this->faker->word(),
            ],
        ];

        $person = Person::factory()->create([
            'nickname' => $data['apelido'],
        ]);

        $personCacheData = json_encode($person->toArray());

        Cache::set('person.' . $person['nickname'], $personCacheData, now()->addDay());

        $response = $this->postJson('/pessoas', $data);

        $response->assertUnprocessable();
    }

    public static function storeInvalidDataDataProvider()
    {
        return [
            [
                [
                    'apelido' => 'ana',
                    'nome' => null,
                    'nascimento' => '1985-09-23',
                    'stack' => null,
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                [
                    'apelido' => null,
                    'nome' => 'Ana Barbosa',
                    'nascimento' => '1985-09-23',
                    'stack' => null,
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                [
                    'apelido' => 'apelido',
                    'nome' => 'Ana Barbosa',
                    'nascimento' => '1985-49-49',
                    'stack' => null,
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                [
                    'apelido' => 'apelido',
                    'nome' => 1,
                    'nascimento' => '1985-01-01',
                    'stack' => null,
                ],
                Response::HTTP_BAD_REQUEST
            ],
            [
                [
                    'apelido' => 'apelido',
                    'nome' => 1,
                    'nascimento' => '1985-01-01',
                    "stack" => [1, "PHP"],
                ],
                Response::HTTP_BAD_REQUEST
            ],
        ];
    }
}
