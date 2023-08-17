<?php

namespace App\Services;

use App\Models\Person;
use Carbon\Carbon;

class PersonService
{
    public function getByUuid(string $uuid)
    {
        return Person::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return Person::query()
            ->create([
                'nickname' => $data['apelido'],
                'name' => $data['nome'],
                'date_of_birth' => Carbon::createFromFormat('Y-m-d', $data['nascimento']),
                'stack' => $data['stack']
            ]);
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
