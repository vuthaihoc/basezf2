<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AdminCP\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NewsController extends AbstractActionController
{
    protected $itemTable;
   public function getItemTable()
   {
      if (!$this->itemTable) {
         $sm = $this->getServiceLocator();

         $this->itemTable = $sm->get('AdminCP\Model\News');
      }
      return $this->itemTable;
   }
   public function indexAction()
   {
       return $this->redirect()->toRoute('admincp',array('controller'=>'news','action'=>'list'));
   }
   public function listAction()
   {
      return new ViewModel(array(
            'news' => $this->getItemTable()->listItem(),
      ));
   }
   public function addAction()
   {
       $request = $this->getRequest();
       //$data['message'] = array();
       if($request->isPost()){
           $posteds = $request->getPost();
           $posted = array();
           foreach($posteds as $key=>$value)
           {
               $posted[$key] = $value;
           }
           if($posted){
               $this->getItemTable()->saveItem($posted);
               $this->redirect()->toRoute('admincp',array('controller'=>'news','action'=>'list'));
           }
       }
      return new ViewModel();
   }
   public function deleteAction()
   {
       $data['message'] = array();
       $route = $this->getEvent()->getRouteMatch();
       $id = $route->getParam('id');
       $check_delete = $this->getItemTable()->deleteItem($id);
       if($check_delete)$this->redirect()->toRoute('admincp',array('controller'=>'news','action'=>'list'));
       else {
           $data['message']['error'] = "Can not delete this record!";
           return new ViewModel($data);
       }
   }
   public function editAction()
   {
       $request = $this->getRequest();
       //$data['message'] = array();
       if($request->isPost()){
           $posteds = $request->getPost();
           $posted = array();
           foreach($posteds as $key=>$value)
           {
               $posted[$key] = $value;
           }
           if($posted){
               $this->getItemTable()->saveItem($posted);
               $this->redirect()->toRoute('admincp',array('controller'=>'news','action'=>'list'));
           }
       }
      $route = $this->getEvent()->getRouteMatch();
      $id = $route->getParam('id');
      $data['news'] = $this->getItemTable()->getItem($id);
      return new ViewModel($data);
   }
}
