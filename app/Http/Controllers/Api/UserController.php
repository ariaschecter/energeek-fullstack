<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userHelper;
    public function __construct()
    {
        $this->userHelper = new UserHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->userHelper->getAllData();

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->userHelper->storeData($validated);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->userHelper->showData($id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->userHelper->updateData($validated, $id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->userHelper->deleteData($id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }
}
