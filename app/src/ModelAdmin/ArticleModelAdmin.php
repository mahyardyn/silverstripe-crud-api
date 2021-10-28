<?php
namespace app\ModelAdmin;

use app\DataObjects\tips;
use SilverStripe\Admin\ModelAdmin;

class TipsModelAdmin extends ModelAdmin
{
    /**
     * managed models
     *
     * @var array
     */
    private static $managed_models = [
        tips::class,
    ];


    private static $url_segment = 'tips';

    private static $menu_title = 'My site tips';
}
