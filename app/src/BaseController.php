<?php

namespace app;

use app\DataObjects\tips;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;


class BaseController extends Controller
{
    private $itemsToFilterFromResponse = [
        'ClassName',
        'RecordClassName',
    ];

    protected function filterResponseDate(array $items)
    {
        $arrayResult = [];
        $arrayTemp = [];
        $arrayRes = array();
        $b = array('Guid', 'Title', 'description', 'created_at', 'updated_at');

        $renameMap = [
            'ID' => 'Guid',
            'Created' => 'created_at',
            'LastEdited' => 'updated_at'
        ];

        $filterTheseFromResponse = array_fill_keys($this->itemsToFilterFromResponse, ""); //fill the keys with empty values

        //clear out the keys we dont need if items has many array items or just 1

        if (count($items) == count($items, COUNT_RECURSIVE)) {
            //pluck out the first array item
            $arrayResult = array_diff_key($items, $filterTheseFromResponse);

            $arrayResult = array_combine(array_map(function ($el) use ($renameMap) {
                return isset($renameMap[$el]) ? $renameMap[$el] : $el;
            }, array_keys($arrayResult)), array_values($arrayResult));


            foreach ($b as $index) {
                $arrayRes[$index] = $arrayResult[$index];
            }
        } else {

            foreach ($items as $item) {
                $arrayTemp[] = array_diff_key($item, $filterTheseFromResponse);
            }

            foreach ($arrayTemp as $item) {
                $arrayResult[] = array_combine(array_map(function ($el) use ($renameMap) {
                    return isset($renameMap[$el]) ? $renameMap[$el] : $el;
                }, array_keys($item)), array_values($item));
            }


            for ($i = 0; $i < count($arrayResult); $i++) {
                foreach ($b as $index) {
                    $arrayRes[$i][$index] = $arrayResult[$i][$index];
                }
            }
        }

        return $arrayRes;
    }

    protected function validateRequest(array $items)
    {
        $validationMessages = [];
        $requiredFields = $this->requiredFields;
        $fillableFields = array_diff_key($items, array_fill_keys($this->fillableFields, ""));



        //required fields
        foreach ($requiredFields as $requiredField) {
            //validate the presence of required fields
            if (!array_key_exists($requiredField, $items)) {
                $validationMessages[] = "$requiredField is required";
            }
        }

        //check fillable fields
        if (count($fillableFields)) {
            foreach ($fillableFields as $key => $value) {
                $validationMessages[] = "$key is not in the fillable fields array";
            }
        }

        //return the validation error messages with a 400 status code and exit
        if (count($validationMessages)) {
            $response = $this->serveJSON($validationMessages, 400);
            echo $response->output();
            exit();
        }
        return true; //all requirements are set
    }


    protected function serveJSON($body, int $statusCode = 200, $statusDescription = null)
    {
        $this->getResponse()->setBody(json_encode($body, JSON_PRETTY_PRINT));
        $this->getResponse()->setStatusCode($statusCode);
        $this->getResponse()->addHeader("Content-Type", "application/json; charset=utf-8");
        // $this->getResponse()->addHeader("Content-Length", "1024");
        // $this->getResponse()->addHeader('Access-Control-Allow-Origin', "*");
        // $this->getResponse()->addHeader("Access-Control-Allow-Headers", "x-requested-with");

        return $this->getResponse();
    }
}
