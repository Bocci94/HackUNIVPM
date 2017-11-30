<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected $_logger;
    protected $_view;

    protected function _initLogging()
    {
      $logger = new Zend_Log();
      $writer = new Zend_Log_Writer_Firebug();
      $logger->addWriter($writer);

      Zend_Registry::set('log', $logger);

      $this->_logger = $logger;
      $this->_logger->info('Bootstrap ' . __METHOD__);
    }

    protected function _initRequest() {
    // Aggiunge un'istanza di Zend_Controller_Request_Http nel Front_Controller
    // che permette di utilizzare l'helper baseUrl() nel Bootstrap.php
    // Necessario solo se la Document-root di Apache non è la cartella public/
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        $request = new Zend_Controller_Request_Http();
        $front->setRequest($request);
    }

    protected function _initViewSettings() {
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        $this->_view->headMeta()->setCharset('UTF-8');
        $this->_view->headMeta()->setName('viewport', 'width=device-width, initial-scale=1');
        $this->_view->headMeta()->appendHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1');
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/fonticons.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/stylesheet.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/font-awesome.min.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/bootstrap.min.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/plugins.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/style.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/responsive.css'));
    }

    protected function _initDefaultModuleAutoloader() {
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('App_');
        $this->getResourceLoader()
                ->addResourceType('modelResource', 'models/resources', 'Resource');
    }

    protected function _initDbParms() {
        include_once (APPLICATION_PATH . '/../include/connectZP.php');
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host' => $HOST,
            'username' => $USER,
            'password' => $PASSWORD,
            'dbname' => $DB
        ));
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }

}