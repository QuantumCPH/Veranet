<?php

/**
 * userguide actions.
 *
 * @package    zapnacrm
 * @subpackage userguide
 * @author     Your name here
 */
 
class client_documentsActions extends sfActions
{	
  public function executeIndex(sfWebRequest $request)
  {
        $c = new Criteria();
        $this->document = ClientdocumentsPeer::doSelect($c);
            
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new UserguideForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
  	
        $this->setTemplate('new');
	$error = '';
	$this->error = '';
	if(isset($_REQUEST['save']) && $_FILES['documentfile']['name']!=''){

		$uploaddir = sfConfig::get('sf_upload_dir').'/documents/';
		$resultQry = date('Y-m-d-h-s');
		$FILE_NAME = $resultQry.'_'.$_FILES['documentfile']['name'];
		$uploadfile = $uploaddir . $resultQry.'_'.basename($_FILES['documentfile']['name']);
		move_uploaded_file($_FILES['documentfile']['tmp_name'], $uploadfile);
                
                $c = new Clientdocuments();
                $c->setTitle($_REQUEST['docTitle']);
                $c->setFilename($FILE_NAME);
                $c->setStatus($_REQUEST['DocStatus']);
                $c->save();

		$this->redirect('client_documents/index');
                
	}elseif(isset($_REQUEST['save']) && $_FILES['documentfile']['name']==''){
		$error = 'fileerror';
		$this->error = 'Error';
	}
  }

  public function executeEdit(sfWebRequest $request)
  {
  	$EditId = $request->getParameter('id');
	$this->editId = $EditId;

        $c = new Criteria();
        $c->add(ClientdocumentsPeer::ID, $EditId);
        $this->doc = ClientdocumentsPeer::doSelectOne($c);
 
	if(isset($_REQUEST['update']) && $_FILES['documentfile']['name']!=''){
			
		$uploaddir = sfConfig::get('sf_upload_dir').'/documents/';
		//Upload Image
		$resultQry = date('Y-m-d-h-s');
		$FILE_NAME = $resultQry.'_'.$_FILES['documentfile']['name'];
		$uploadfile = $uploaddir . $resultQry.'_'.basename($_FILES['documentfile']['name']);
		move_uploaded_file($_FILES['documentfile']['tmp_name'], $uploadfile);
                
                $this->doc->setTitle($_REQUEST['docTitle']);
                $this->doc->setFilename($FILE_NAME);
                $this->doc->setStatus($_REQUEST['DocStatus']);
                $this->doc->save();
				
		$this->redirect('client_documents/index');
	}elseif(isset($_REQUEST['update']) && $_FILES['documentfile']['name']==''){
		mysql_query("UPDATE clientdocuments SET title = '".$_REQUEST['docTitle']."' WHERE id ='".$editid."' ");		
		$this->redirect('client_documents/index');
	}
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($userguide = UserguidePeer::retrieveByPk($request->getParameter('id')), sprintf('Object userguide does not exist (%s).', $request->getParameter('id')));
    $this->form = new UserguideForm($userguide);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
  	
        $request->checkCSRFProtection();
	$deleteId = $request->getParameter('id');
        $doc = ClientdocumentsPeer::retrieveByPk($deleteId);
        $doc->delete();

        $this->redirect('client_documents/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
//        $file = $this->form->getValue('image');
//        $path = sfConfig::get('sf_upload_dir').'/userguide/'.$file.'_'.rand(1, 10);
//        $extension = $file->getExtension($file->getOriginalExtension());
//        $file->save($path.'.'.$extension);

        //$this->form->updateObject();
                        
      $userguide = $form->save();

      $this->redirect('client_documents/edit?id='.$userguide->getId());
    }
  }
}
