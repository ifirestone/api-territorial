<?php

namespace App\Tools;

use Illuminate\Http\Request;
use App\Exceptions\SomethingWentWrong;
use App\Exceptions\NotEnable;
use Carbon\Carbon;

trait CloudinaryTrait
{

    public function uploadCloudinary(Request $request, $fileName)
    {
        if (env('CLOUDINARY_ENABLE')) {
            if (!$request->file($fileName)) {
                return $file = array(
                    "name" => null,
                    "id" => null,
                    "url" => null,
                    "ext" => null,
                    "size" => null,
                );
            }

            try {
                $name = strtoupper('DOC-' . auth()->user()->id . "-" . Carbon::now()->format('Y-m-d') . "-" . time() . "." . $request->file($fileName)->getClientOriginalExtension());
                $archivo = $request->file($fileName)->storeOnCloudinaryAs(ENV('CLOUDINARY_FOLDER'));

                $file = array(
                    "name" => $name,
                    "id" => $archivo->getPublicId(),
                    "url" => $archivo->getSecurePath(),
                    "ext" => $request->file($fileName)->getClientOriginalExtension(),
                    "size" => $request->file($fileName)->getSize(),
                );

                return $file;
            } catch (\Throwable $th) {
                throw new SomethingWentWrong($th);
            }
        } else {
            throw new NotEnable();
        }
    }

    public function destroyCloudinary($publicId)
    {
        if (env('CLOUDINARY_ENABLE')) {
            if ($publicId != null) {
                try {
                    cloudinary()->destroy($publicId);
                } catch (\Throwable $th) {
                    throw new SomethingWentWrong($th);
                }
            } else {
                return true;
            }
        } else {
            throw new NotEnable();
        }
    }
}