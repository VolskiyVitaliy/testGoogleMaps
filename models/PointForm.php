<?php


namespace app\models;

use yii\base\Model;

class PointForm extends Model
{
    public $longitude;
    public $latitude;
    public $radius;

    public function rules()
    {
        return [
            [['longitude', 'latitude','radius'], 'required'],
            [['longitude', 'latitude','radius'], 'number'],
            ['longitude', 'compare', 'compareValue' => 180, 'operator' => '<=', 'type' => 'number'],
            ['longitude', 'compare', 'compareValue' => -180, 'operator' => '>=', 'type' => 'number'],
            ['latitude', 'compare', 'compareValue' => 90, 'operator' => '<=', 'type' => 'number'],
            ['latitude', 'compare', 'compareValue' => -90, 'operator' => '>=', 'type' => 'number'],
        ];
    }
}