<?php

namespace api\resources;

use common\models\Country;

class CountryResource extends  Country
{
    public function fields()
    {
        $lang = \Yii::$app->language;
        $suffix = $lang === 'ar' ? '_ar' : '_en';
        return [
            'id',
            'code',
            'name'=>function() use ($suffix){
                return $this->{'name' . $suffix};
            },
            'nationality'=>function() use ($suffix){
                return $this->{'nationality' . $suffix};
            },
        ];
    }
}
