<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HomeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $homeHelper;
    public function __construct()
    {
        $this->homeHelper = new HomeHelper();
    }
    public function store(TaskRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->homeHelper->storeTasks($validated);

        if (!$response["status"]) :
            return response()->failed(message: $response["message"], dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"], message: $response['message'], httpCode: $response['status_code']);

    }
}
