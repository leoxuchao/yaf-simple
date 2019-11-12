<?php

use Yaf\Loader;
use Yaf\Registry;
use Yaf\Application;
use Yaf\Dispatcher;
use Yaf\Bootstrap_ABstract;

use Illuminate\Database\Capsule\Manager as IlluminateCapsule;

 
class Bootstrap extends Bootstrap_Abstract{
    public static $capsule;

    public function _initConfig() {
		$arrConfig = Application::app()->getConfig();
		Registry::set('config', $arrConfig);
	}

    /**
     * 加载自定义插件 2019-10-06
     * @param Dispatcher $dispatcher
     */
	public function _initPlugin(Dispatcher $dispatcher) {
		$leoxuchaoPlugin = new LeoxuchaoPlugin();
		$dispatcher->registerPlugin($leoxuchaoPlugin);
	}

	public function _initRoute(Dispatcher $dispatcher) {
		
	}
	
	public function _initView(Dispatcher $dispatcher){
        //在这里注册自己的view控制器，例如smarty,firekylin
        $dispatcher->autoRender(false);
	}

    /**
     * 启用PSR4类加载机制 2019-10-06
     * @param Dispatcher $dispatcher
     */
	public function _initLoader(Dispatcher $dispatcher)
	{
		Loader::import(APPLICATION_PATH . '/vendor/autoload.php');
	}

    /**
     * 注册全局数据库操作引擎 2019-10-06  如果不需要使用，可以注释掉
     * @throws Exception
     */
	public function _initEloquent(){
        $config = Registry::get('config');

        if (!$config->database) {
            throw new Exception("Must configure database in application.ini first");
        }

        $config = $config->database->toArray();
        self::$capsule = new IlluminateCapsule();
        self::$capsule->addConnection($config);
        self::$capsule->setAsGlobal();
        self::$capsule->bootEloquent();

    }


}
