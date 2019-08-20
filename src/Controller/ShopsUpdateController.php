<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class RegistsController extends AppController {

	    public $components = array(
        'ImgProcess' => array(),
    );


    public function initialize()
    {
      	parent::initialize();
		$this->set('title','投稿 | Fave');

   		$this->session = $this->getRequest()->getSession();

    }

}