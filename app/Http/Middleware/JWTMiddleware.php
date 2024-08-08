<?php

namespace App\Http\Middleware;

use App\Constant\StatusCodeConstant;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        try {
            $user = auth('api')->user();

            $result = [
                "status"  => true,
                "message" => null,
                "dev"     => null
            ];

            if ($user == null) :
                $result = [
                    "status"  => false,
                    "message" => "Token yang anda gunakan tidak valid",
                    "dev"     => null
                ];
            endif;

            if (!$result["status"]) :
                return response()->failed(httpCode: StatusCodeConstant::UNAUTHORIZED_CODE, message: $result["message"]);
            else :
                return $next($request);
            endif;

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) :
                $message = "Token yang anda gunakan tidak valid";
                return response()->failed(error: $message, httpCode: StatusCodeConstant::UNAUTHORIZED_CODE, message: $message);
            elseif ($e instanceof TokenExpiredException) :
                $message = "Token anda telah kedaluwarsa, Silakan login ulang";
                return response()->failed(error: $message, httpCode: StatusCodeConstant::FORBIDDEN_CODE, message: $message);
            else :
                $message = "Silakan login terlebih dahulu";
                $dev = $e->getMessage() . " at line " . $e->getLine() . " in " . $e->getFile();
                return response()->failed(error: $message, httpCode: StatusCodeConstant::UNAUTHORIZED_CODE, message: $message, dev: $dev);
            endif;
        }
    }
}
