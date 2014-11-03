<?php

App::uses('TaxonomyAppController', 'Taxonomy.Controller');

class TaxonomyController extends TaxonomyAppController { 

	public function admin_index(){
	$this->redirect(array('controller'=>'vocabularies','action'=>'index'));
	}
} 