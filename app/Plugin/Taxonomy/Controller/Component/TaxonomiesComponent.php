<?php

App::uses('Component', 'Controller');

/**
 * Taxonomies Component
 */
class TaxonomiesComponent extends Component {

/**
 * Other components used by this component
 *
 * @var array
 * @access public
 */


/**
 * Types for layout
 *
 * @var string
 * @access public
 */
	public $typesForLayout = array();

/**
 * Vocabularies for layout
 *
 * @var string
 * @access public
 */
	public $vocabulariesForLayout = array();

/**
 * Startup
 *
 * @param object $controller instance of controller
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
	}

	public function beforeRender(Controller $controller) {
		$this->controller = $controller;
		$this->controller->set('types_for_layout', $this->typesForLayout);
		$this->controller->set('vocabularies_for_layout', $this->vocabulariesForLayout);
	}

}
