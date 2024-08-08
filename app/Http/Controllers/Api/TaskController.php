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
    public function store(TaskRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();

        $response = $this->taskHelper->storeTasks($validated);

        if (!$response["status"]) :
            return response()->failed(message: $response["message"], dev: $response["dev"]);
        endif;
        return response()->success(data: $response["data"], message: $response['message'], httpCode: $response['status_code']);

    }
}
