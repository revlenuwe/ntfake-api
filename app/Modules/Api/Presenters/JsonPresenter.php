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

    protected $allowedStatusCodes = [
        200,201,202,204,301,302,307,308,400,401,402,403,404,405,409,500,502,503,504
    ];

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
                    'api' => $_SERVER['HTTP_HOST'].$this->link('Json:returnResponse', $fakeApi->sign)
                ]);
            }
            $this->sendResponseWithCode(['error' => 'validation error'],IResponse::S400_BAD_REQUEST);
        }
        $this->sendResponseWithCode(['error' => 'method not allowed'],IResponse::S405_METHOD_NOT_ALLOWED);
    }

    public function actionReturnResponse(string $sign) {
        $fakeApi = $this->json->getBySign($sign)->fetch();
        if($fakeApi) {
            if($this->request->isMethod($fakeApi->method)){
                $this->sendResponseWithCode(json_decode($fakeApi->json),$fakeApi->status_code);
            }
            $this->sendResponseWithCode(['error' => 'method not allowed'],IResponse::S405_METHOD_NOT_ALLOWED);
        }
        $this->sendResponseWithCode(['error' => 'endpoint does not exist'],IResponse::S404_NOT_FOUND);
    }

    private function createFakeApi($query) {
        return $this->json->create([
            'sign' => $this->randomStr(6),
            'method' => $query->method,
            'status_code' => $query->code,
            'json' => $this->normalizeJson($query->body),
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
        return $this->isJson($query->body) && Validators::isInRange($query->method,['get','post']) && in_array($query->code,$this->allowedStatusCodes);
    }

    function isJson($str) {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function normalizeJson($string) {
        return str_replace("\\n", "", $string);
    }

    private function randomStr($len) {
        return bin2hex(random_bytes($len));
    }
}