<?php

namespace App\Providers;

use App\Constant\StatusCodeConstant;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        Response::macro("success", function ($data = [], $message = "", $httpCode = StatusCodeConstant::SUCCESS_CODE, $dev = null, $meta = null) {
            if (empty($message)) :
                // Untuk setting pesan default
                $apiMethod = request()->getMethod();
                switch ($apiMethod) :
                    case "GET":
                        $message = "Data berhasil diambil";
                        break;
                    case "PATCH":
                        $message = "Data berhasil diperbarui";
                        break;
                    case "PUT":
                        $message = "Data berhasil diubah";
                        break;
                    case "POST":
                        $httpCode = StatusCodeConstant::CREATED_CODE;
                        $message = "Data berhasil ditambahkan";
                        break;
                    case "DELETE":
                        $message = "Data berhasil dihapus";
                        break;
                endswitch;
            endif;

            $result = [
                "status_code" => $httpCode,
                "message"     => $message,
                "data"        => $data,
            ];

            if (!empty($meta)) :
                $result["meta"] = $meta;
            endif;

            $result["dev"] = $dev;
            return Response::make($result, $httpCode);
        });

        Response::macro("failed", function ($error = [], $httpCode = StatusCodeConstant::ERROR_CODE, $message = "", $dev = null, $data = null) {
            if (empty($message)) :
                // Untuk setting pesan default
                $apiMethod = request()->getMethod();
                switch ($apiMethod) :
                    case "GET":
                        $message = "Gagal mengambil data";
                        break;
                    case "PATCH":
                        $message = "Gagal memperbarui data";
                        break;
                    case "PUT":
                        $message = "Gagal memperbarui data";
                        break;
                    case "POST":
                        $message = "Gagal menambahkan data";
                        break;
                    case "DELETE":
                        $message = "Gagal menghapus data";
                        break;
                    default:
                        $message = "Terjadi kesalahan pada server";
                        break;
                endswitch;
            endif;

            if (is_array($error)) :
                $arrError = $error;
            else :
                $arrError = [];
                $tmpError = (array) $error;
                foreach ($tmpError as $val) :
                    foreach ((array) $val as $v) :
                        if ($v !== ":message") :
                            $arrError[] = $v;
                        endif;
                    endforeach;
                endforeach;
            endif;

            return Response::make([
                "status_code" => $httpCode,
                "data"        => $data,
                "message"     => $message,
                "errors"      => $arrError,
                "dev"         => $dev
            ], $httpCode);
        });
    }
}
