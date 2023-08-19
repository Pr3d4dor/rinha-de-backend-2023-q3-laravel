<?php

namespace App\Services;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class PersonService
{
    public function getByUuid(string $uuid)
    {
        if ($personCacheData = Cache::get('person.' . $uuid)) {
            $person = Person::query()->make(
                json_decode($personCacheData, true)
            );

            return $person;
        }

        abort(Response::HTTP_NOT_FOUND, 'Not Found!');
    }

    public function create(array $data)
    {
        $formattedData = [
            'uuid' => uuid_create(),
            'nickname' => $data['apelido'],
            'name' => $data['nome'],
            'date_of_birth' => Carbon::createFromFormat('Y-m-d', $data['nascimento']),
            'stack' => $data['stack']
        ];

        $person = Person::query()->make($formattedData);

        $personCacheData = json_encode($person->toArray());

        Cache::set('person.' . $person['uuid'], $personCacheData, now()->addDay());
        Cache::set('person.' . $person['nickname'], $personCacheData, now()->addDay());

        dispatch(function () use ($formattedData) {
            Person::query()->create($formattedData);
        });

        return $person;
    }

    public function search(string $term, int $limit = 50)
    {
        return Person::query()
            ->where('nickname', 'LIKE', "%$term%")
            ->orWhere('name', 'LIKE', "%$term%")
            ->orWhere('stack', 'LIKE', "%$term%")
            ->limit($limit)
            ->get();
    }

    public function count()
    {
        return Person::query()
            ->count();
    }
}
