<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CategoryHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    private $categoryHelper;
    public function __construct()
    {
        $this->categoryHelper = new CategoryHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->categoryHelper->getAllData();

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->categoryHelper->storeData($validated);

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
        $response = $this->categoryHelper->showData($id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->categoryHelper->updateData($validated, $id);

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
        $response = $this->categoryHelper->deleteData($id);

        if (!$response["status"]) :
            return response()->failed(dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"]);
    }
}
