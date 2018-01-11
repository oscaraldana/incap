<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/LandingServices for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LandingServices\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use LandingServices\Clases\LandingServices;

class LandingServicesController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }
    
    public function uploadContentAction()
    {
        $this->layout('layout/ajax_layout.phtml');
        
        $LandingServices = new LandingServices($this);
        
        $variables_post = $this->getRequest()->getPost();
        
        $landing_page_id = $this->params()->fromRoute('landing_page_id', 0);
        
        $fileInfo =  $this->getRequest()->getFiles()->toArray();
        
        $result_upload_zip = $LandingServices->uploadZip($landing_page_id,$variables_post["token"],$fileInfo);
        
        $this->layout()->escribir_log->escribirLog($this,'INFO',"Upload Zip: ".var_export($result_upload_zip,true)."\n".' LandingId:'.$landing_page_id."\n".'Token:'.$variables_post["token"]."\n".'Archivo:'.var_export($fileInfo,true).var_export($_SERVER, true));
        
        return new ViewModel(array(
        
            'code' => $result_upload_zip['code'],
        
            'message' => $result_upload_zip['message'],
        
        ));
    
    }
    
    public function renewCacheAction()
    {
        
        $this->layout('layout/ajax_layout.phtml');
    
        $LandingServices = new LandingServices($this);
        
        $variables_post = $this->getRequest()->getPost();
        
        $landing_page_id = $this->params()->fromRoute('landing_page_id', 0);
        
        $token_param = isset($variables_post["token"])?$variables_post["token"]:$this->params()->fromRoute('token',0);
        
        $result_renew_cache = $LandingServices->renewCache($landing_page_id, $token_param);
        
        $this->layout()->escribir_log->escribirLog($this,'INFO',"Renew Cache: ".var_export($result_renew_cache,true)."\n".' LandingId:'.$landing_page_id."\n".'Token:'.$token_param);
        
        return new ViewModel(array(
        
            'code' => $result_renew_cache['code'],
        
            'message' => $result_renew_cache['message'],
        
        ));
    
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /landingServices/landing-services/foo
        return array();
    }
}
