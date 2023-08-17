<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PersonResource;
use App\Services\PersonService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonSearchController extends Controller
{
    public function __invoke(PersonService $personService, Request $request)
    {
        $term = $request->query('t');

        abort_if(empty($term), Response::HTTP_BAD_REQUEST);

        $result = $personService->search($term);

        return PersonResource::collection($result);
    }
}
