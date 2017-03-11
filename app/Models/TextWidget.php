<?php

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
            2 => 'Наши преимущества',
            3 => 'Помощь флориста',
            4 => 'Наши преимущества в категориях',
            5 => 'Набор со свободной стоимостью',
            6 => 'Первый баннер в категориях',
            7 => 'С этими товарами часто берут',
            8 => 'Баннер в регистрации'
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