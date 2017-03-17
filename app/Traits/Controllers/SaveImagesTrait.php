<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 16.03.17
 * Time: 22:24
 */

namespace App\Traits\Controllers;


use App\Models\Image;

trait SaveImagesTrait
{

    private function _processImages($model) {

        $imodel = Image::where('imagable_id', $model->id)->where('imagable_type', "App\\Models\\" . class_basename($model))->first();

        $images = request('images', []);

        if($imodel) {

            $imodel->images = $images;

            $imodel->save();

        } else {

            $imodel = new Image();

            $imodel->fill([
                'images' => $images
            ]);

            $model->images()->save($imodel);


        }

    }

}