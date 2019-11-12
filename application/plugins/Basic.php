<?php

use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use Yaf\Plugin_Abstract;

class BasicPlugin extends Plugin_Abstract {

	public function routerStartup(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function routerShutdown(Request_Abstract $request, Response_Abstract $response) 
	{
        $arrParam = $request->getPost() ? $request->getPost() : $request->getQuery();
        foreach ($arrParam as &$item) {
            if(is_string($item)){
                $item = trim($item);
            }
        }
        $request->setParam('params',$arrParam);
		
	}

	public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function preDispatch(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function postDispatch(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}
}
