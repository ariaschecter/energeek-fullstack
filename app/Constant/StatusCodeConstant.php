<?php

namespace App\Constant;

class StatusCodeConstant
{
    const SUCCESS_CODE = 200;
    const CREATED_CODE = 201;

    const BAD_REQUEST_CODE = 400;
    const UNAUTHORIZED_CODE = 401;
    const FORBIDDEN_CODE = 403;
    const NOT_FOUND_CODE = 404;
    const CONFLICT_CODE = 409;
    const CONTENT_TOO_LARGE_CODE = 413;
    const ERROR_CODE = 422;
    const LOCKED_CODE = 423;
    const TOO_MANY_REQUEST_CODE = 429;

    const SUCCESS_MESSAGE = 'Berhasil mengambil data';
}
