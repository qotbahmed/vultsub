<?php

namespace api\resources;

use common\models\Faq;
use common\models\Sponsors;


class SponsorResource extends Sponsors
{
    public function fields()
    {
        return [
            'id',
            'title',
            'image' => function () {
                return $this->getImage();
            },

            ];
    }
}
