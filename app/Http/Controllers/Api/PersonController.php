<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PersonResource;
use Illuminate\Http\Request;
use App\Http\Requests\Api\CreatePersonRequest;
use App\Services\PersonService;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    private PersonService $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function show(Request $request, string $uuid)
    {
        $person = $this->personService->getByUuid($uuid);

        return new PersonResource($person);
    }

    public function store(CreatePersonRequest $createPersonRequest)
    {
        $person = $this->personService->create($createPersonRequest->validated());

        $response = (new PersonResource($person))
            ->toResponse($createPersonRequest);

        $response
            ->setStatusCode(Response::HTTP_CREATED);

        $response->header('Location', sprintf('/pessoas/%s', $person['uuid']));

        return $response;
    }
}
