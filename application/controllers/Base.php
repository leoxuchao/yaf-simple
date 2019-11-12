<?php
    use Yaf\Controller_Abstract;

    class BaseController extends Controller_Abstract {

        public function init(){

            $this->params = $this->getRequest()->getParams()['params'];

        }

        public function echoSuccessData($data = array())
        {
            if (!is_array($data) && !is_object($data)) {
                $data = [$data];
            }
            $this->echoAndExit(0, "success", $data);
        }


        public function echoAndExit($code, $msg, $data = [])
        {
            $data = $this->clearNullNew($data);
            if (is_null($data) && !is_numeric($data)) {
                $data = [];
            }

            $echoList = ['code' => $code, 'msg'  => $msg, 'data' => $data];

            if (!$this->getRequest()->isCli()) {
                $this->getResponse()->setHeader('Content-Type', 'application/json;charset=utf-8');
            }
            $this->getResponse()->setBody(json_encode($echoList));
        }

        public function clearNullNew($data)
        {
            foreach ($data as $key => $value) {
                $keyTemp = lcfirst($key);
                if ($keyTemp != $key) {
                    unset($data[$key]);
                    $data[$keyTemp] = $value;
                    $key            = $keyTemp;
                }

                if (is_array($value) || is_object($value)) {
                    if (is_object($data)) {
                        $data->$key = $this->clearNullNew($value);
                    } else {
                        $data[$key] = $this->clearNullNew($value);
                    }
                } else {
                    if (is_null($value) && !is_numeric($value)) {
                        $value = "";
                    }
                    if (is_numeric($value)) {
                        $value = strval($value);
                    }
                    $data[$key] = $value;
                }
            }
            return $data;
        }

        protected function throwException($name, $code)
        {
            throw new Exception($name, $code);
        }



    }
