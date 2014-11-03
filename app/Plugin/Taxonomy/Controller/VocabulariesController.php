<?php

App::uses('TaxonomyAppController', 'Taxonomy.Controller');

/**
 * Vocabularies Controller
 */
class VocabulariesController extends TaxonomyAppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Vocabularies';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Taxonomy.Vocabulary');

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$this->set('title_for_layout', __d('Taxonomy', 'Vocabularies'));

		$this->Vocabulary->recursive = 0;
			
	    $this->paginate = array('recursive' => 0,
            'limit' =>100,
            'order' => array('Vocabulary.weight' => 'ASC'));
        $vocabularies = $this->paginate('Vocabulary');

		$this->set('vocabularies',$vocabularies);
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		$this->set('title_for_layout', __d('Taxonomy', 'Add Vocabulary'));

		if (!empty($this->request->data)) {
			$this->Vocabulary->create();
			if ($this->Vocabulary->save($this->request->data)) {
				$this->Session->setFlash(__d('Taxonomy', 'The Vocabulary has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('Taxonomy', 'The Vocabulary could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}


	}

/**
 * Admin edit
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_edit($id = null) {
		$this->set('title_for_layout', __d('Taxonomy', 'Edit Vocabulary'));

		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__d('Taxonomy', 'Invalid Vocabulary'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Vocabulary->save($this->request->data)) {
				$this->Session->setFlash(__d('Taxonomy', 'The Vocabulary has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('Taxonomy', 'The Vocabulary could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Vocabulary->findById($id);
		}
   $this->render('admin_add');
	}

/**
 * Admin delete
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__d('Taxonomy', 'Invalid id for Vocabulary'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Vocabulary->delete($id)) {
			$this->Session->setFlash(__d('Taxonomy', 'Vocabulary deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
	}

/**
 * Admin moveup
 *
 * @param integer $id
 * @param integer $step
 * @return void
 * @access public
 */
	public function admin_moveup($id, $step = 1) {
		if ($this->Vocabulary->moveUp($id, $step)) {
			$this->Session->setFlash(__d('Taxonomy', 'Moved up successfully'), 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__d('Taxonomy', 'Could not move up'), 'default', array('class' => 'error'));
		}

		$this->redirect(array('action' => 'index'));
	}

/**
 * Admin moveup
 *
 * @param integer $id
 * @param integer $step
 * @return void
 * @access public
 */
	public function admin_movedown($id, $step = 1) {
		if ($this->Vocabulary->moveDown($id, $step)) {
			$this->Session->setFlash(__d('Taxonomy', 'Moved down successfully'), 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__d('Taxonomy', 'Could not move down'), 'default', array('class' => 'error'));
		}

		$this->redirect(array('action' => 'index'));
	}

   /**
    * 
    * searching voc for admin
    */
   public function admin_search_page(){
   		$this->layout = false;
   		if(isset($this->data) && !empty($this->data))
   		{
   			
   			$searchData =trim($this->data['mssg']);
   			$cond = array();
   		   if(!empty($searchData)){
            $cond[] = array( 
                'or' => array(
                        "Vocabulary.title LIKE" => "%" . $searchData . "%",
                        "Vocabulary.id" => $searchData,
            			//"Page.views" => $searchData,
                    ));
            }
	
		
            $this->paginate = array('recursive' => 0,
                'conditions' => $cond,
                'order' => array('Vocabulary.weight' => 'ASC'),
                'limit' => 100
             );
            $vocabularies = $this->paginate('Vocabulary');
        	$this->set('vocabularies',$vocabularies);
   		}
   }
}
