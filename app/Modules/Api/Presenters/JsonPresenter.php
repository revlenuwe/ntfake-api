<?php


namespace App\Modules\Api\Presenters;


use App\Models\Json;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Nette\Http\IResponse;
use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Utils\Validators;

class JsonPresenter extends Presenter
{
    protected $request;
    protected $response;
    protected $json;

    protected $allowedStatusCodes = [200,201,202,301,302,307,308,400,401,402,403,404,500,502];

    public function __construct(Request $request,Response $response,Json $json){
        $this->request = $request;
        $this->response = $response;
        $this->json = $json;
    }

    public function actionCreate() {
        if($this->request->isMethod('POST')){
            $query = json_decode($this->request->getRawBody());

            if($this->validateQuery($query)) {//getPost?
                $fakeApi = $this->createFakeApi($query);

                $this->sendJson([
                    'api' => $_SERVER['HTTP_HOST'].'/api/'.$fakeApi->sign//$this->link('Api:Json:response', $fakeResponse->sign)
                ]);
            }

            $this->sendResponseWithCode(['error' => 'validation error'],IResponse::S400_BAD_REQUEST);
//            $this->response->setCode(IResponse::S400_BAD_REQUEST);
//            $this->sendResponse(new JsonResponse(['error' => 'validation error']));
        }
//        $this->response->setCode(IResponse::S405_METHOD_NOT_ALLOWED);
//        $this->sendResponse(new JsonResponse(['error' => 'method not allowed']));
        $this->sendResponseWithCode(['error' => 'method not allowed'],IResponse::S405_METHOD_NOT_ALLOWED);
    }

    private function createFakeApi($query) {
        return $this->json->create([
            'sign' => $this->randomStr(6),
            'method' => $query->method,
            'status_code' => $query->code,
            'json' => $query->body,
        ]);
    }

    private function sendResponseWithCode($response,$code){
        $this->response->setCode($code);
        $this->sendResponse(new JsonResponse($response));
    }
    /*
     * require https://github.com/andersao/laravel-validator
     */
    public function validateQuery($query) {
        return $this->isJson($query->body) && Validators::isInRange($query->method,['get','post']) && Validators::isInRange($query->code,$this->allowedStatusCodes);
    }

    function isJson($str) {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function randomStr($len) {
        return bin2hex(random_bytes($len));
    }
}