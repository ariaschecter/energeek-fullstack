<?php

namespace App\Http\Controllers\Api;

use App\Helpers\TaskHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $taskHelper;
    public function __construct()
    {
        $this->taskHelper = new TaskHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->taskHelper->getAllData();

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->taskHelper->storeData($validated);

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
        $response = $this->taskHelper->showData($id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->taskHelper->updateData($validated, $id);

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
        $response = $this->taskHelper->deleteData($id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }
}
