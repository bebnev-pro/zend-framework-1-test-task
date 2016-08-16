<?php

class ModifyController extends Zend_Controller_Action
{
    
    public function init()
    {
        // model instance
        $this->user = new Application_Model_User();
    }

    public function indexAction()
    {
        // get id from get param - if exists
        $id = $this->_getParam('id');
        
        // pass user with id to view
        $this->view->user = $this->user->getUser($id);
    }

    public function addAction()
    {
        // disable view
        $this->_helper->viewRenderer->setNoRender();
        
        // get all params from form
        $this->user->name = $this->_getParam('name');
        $this->user->setRegistrationDate($this->_getParam('registration_date'));
        $this->user->role = $this->_getParam('role');
        $this->user->setActive($this->_getParam('active'));
        
        // save model
        $id = $this->_getParam('id');
        $this->user->save($id);
        
        // redirect to index controller
        $this->_redirect('/');
    }

    public function deleteAction()
    {
        // disable view
        $this->_helper->viewRenderer->setNoRender();
        
        // delete row with id from get parameter
        $id = $this->_getParam('id');
        $this->user->deleteUser($id);
        
        // redirect to index controller
        $this->_redirect('/');
    }


}





