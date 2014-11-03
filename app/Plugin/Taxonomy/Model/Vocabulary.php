<?php

App::uses('TaxonomyAppModel', 'Taxonomy.Model');

class Vocabulary extends TaxonomyAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Vocabulary';

	public $actsAs = array(
		'Taxonomy.Ordered',
	);
	
/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'title' => array(
			'rule' => array('minLength', 1),
			'message' => 'Title cannot be empty.',
		),
		'alias' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This alias has already been taken.',
			),
			'minLength' => array(
				'rule' => array('minLength', 1),
				'message' => 'Alias cannot be empty.',
			),
		),
	);



/**
 * Model associations: hasMany
 */
	public $hasMany = array(
		'Taxonomy' => array(
			'className' => 'Taxonomy.Taxonomy',
			'dependent' => true,
		),
	);

}
