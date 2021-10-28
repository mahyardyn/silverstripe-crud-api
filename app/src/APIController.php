<?php

namespace app;

use app\DataObjects\tips;
use app\BaseController;
use SilverStripe\ORM\Map;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use Symfony\Component\VarDumper\VarDumper;

class APIController extends BaseController
{
    private static $allowed_actions = [
        'index',
        'tips',
        'createTip',
        'updateTip',
        'deleteTip'
    ];

    protected $requiredFields = [
        "Title",
        "description",
    ];

    protected $fillableFields = [
        "Title",
        "description",
    ];

    public function index()
    {
        return $this->serveJSON(["The Tips JSON API Server is online :)"]);
    }

    public function tips(HTTPRequest $request)
    {
        //do this for GET request

        if ($request->isGET()) {
            $tipID = $request->param("ID");
            if ($tipID) {
                $tip = tips::get()->byID($tipID) ? tips::get()->byID($tipID)->toMap() : null;
            } else {
                $tip = tips::get()->toNestedArray();
            }

            if ($tip) {
                $arrayResult = $this->filterResponseDate($tip);
                return $this->serveJSON($arrayResult);
            } else {
                return $this->serveJSON("Sorry, that tip cannot be found", 404);
            }
        }
        elseif ($request->isDELETE()) {
                //delete a tip
                return $this->deleteTip($request);
            }

        elseif ($request->isPUT() ) {
            //update a tip
            return $this->updateTip($request);
        }
        elseif ($request->isPOST()) {
            //New tip
            return $this->createTip($request);
        }

        return $this->serveJSON("Sorry, this REST endpoint only responds to GET, POST & PUT requests", 400);
    }

    private function createTip(HTTPRequest $request)
    {
        if ($postedVars = $request->getVars()) {
            //validate the request
            $this->validateRequest($postedVars);

            //create the article
            $tip = tips::create($postedVars);
            $tip->write();

            //serve the fresh Tip with status 200
            return $this->serveJSON($this->filterResponseDate($tip->toMap()), 200);
        }
        return $this->serveJSON("You must specify the body of your POST request", 400);
    }

    public function updateTip(HTTPRequest $request)
    {
        if ($request->isPUT() ) {
            if ($request->isPUT()) {
                //parse the request body as this is a PUT request
                $requestVars = $request->getVars();

                //validate the request
                $this->validateRequest($requestVars);
            }

            //update the tip
            $tip = tips::get()->byID($request->param("ID"));
            if ($tip) {

                //fill in the tip based on the fillable keys we have defined
                foreach ($this->fillableFields as $field) {
                    $tip->$field = $requestVars[$field];
                }
                $tip->write();
            } else {
                return $this->serveJSON("Sorry, that tip cannot be found", 404);
            }

            //serve the fresh Tip with status 200
            return $this->serveJSON($this->filterResponseDate($tip->toMap()), 200);
        }
        return $this->serveJSON("You must specify the body of your PUT or POST VARS for POST request request", 400);
    }

    private function deleteTip(HTTPRequest $request)
    {
        if ($request->isDELETE()) {
            $tipID = $request->param("ID");
            $tip = tips::get()->byID($tipID);
            if ($tip) {
                $tip->delete();
                return $this->serveJSON("A Tip with ID $tipID deleted", 200);
            }
            return $this->serveJSON("That tip cannot be found ", 404);

        }
        return $this->serveJSON("You must define a DELETE request to delete this tip", 400);
    }

}
