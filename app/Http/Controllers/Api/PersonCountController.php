<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PersonService;

class PersonCountController extends Controller
{
    public function __invoke(PersonService $personService)
    {
        $result = $personService->count();

        return response($result);
    }
}
