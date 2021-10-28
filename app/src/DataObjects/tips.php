<?php

namespace app\DataObjects;

use SilverStripe\Control\Director;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;

class tips extends DataObject
{

    private static $table_name = "tips";

    private static $db = [
        "Title" => "Varchar(255)",
        "description" => "Text",
    ];



    private static $api_field_mapping = [
        'created_at' => 'Created',
        'updated_at' => 'LastEdited',
        'Guid' => 'ID',
    ];


    public static function GetNameSpace()
    {
        return __NAMESPACE__ . '\\';
    }

    public  function requireDefaultRecords()
    {
        //let us create our selfs a few news items when running this in dev mode
        //when no Tips exists in dev or test mode
        if (Director::isDev() || Director::isTest()) {

            if (!static::get()->first()) {

                $newsItesmToCreate = 5;

                for ($i = 1; $i <= $newsItesmToCreate; $i++) {
                    $article = new self();
                    $article->Title = "Title no. $i";
                    $article->description = "This is description no. $i";
                    $article->write();
                    DB::alteration_message("New Tip created. no: $i", 'created');
                }
            }
        }
    }
}
