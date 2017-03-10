<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Models;

use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TextWidget
 * @package App\Models
 */
class TextWidget extends Model
{

    public static function getAllowed() {
        return array(
            0 => 'Футер',
            1 => 'Цветочная подписка',
            2 => 'Наши преимущества'
        );
    }

    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = ['title', 'content'];

    /**
     * @var array
     */
    protected $fillable = ['layout_position', 'status', 'title', 'content'];

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }
}