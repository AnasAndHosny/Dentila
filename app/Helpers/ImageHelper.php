<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ImageHelper
{
    public static function store($request)
    {
        $image = $request->file('image');
        $image_path = null;
        if ($request->hasfile('image')) {
            $image_path = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_path);
            $image_path = 'images/' . $image_path;;
        }

        return $image_path;
    }

    public static function update($request, Model $model)
    {
        // update only if user send a new image (even if it's null) or keep the current image
        if ($request->has('image')) {
            self::destroy($model);
            return self::store($request);
        }
        return $model['image'];
    }

    public static function destroy(Model $model): bool
    {
        $image = $model['image'];
        if (File::exists($image)) {
            File::delete($image);
            return true;
        }
        return false;
    }
}
