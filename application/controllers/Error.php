<?php

class ErrorController extends BaseController
{
	public function errorAction($exception) 
	{
		//$this->getView()->assign("exception", $exception);


        $debugInfo = [
            "message" => $exception->getMessage(),
            "code" => $exception->getCode()
        ];
        $errorCode = $exception->getCode() ? $exception->getCode():1;
        $errorMsg = $exception->getMessage();

        $this->echoAndExit($errorCode, $errorMsg);
	}
}
