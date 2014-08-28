<?php
/**
 * PostFixture
 *
 */
class PostFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 50),
		'body' => array('type' => 'text', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(

		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-08-27 11:00:03',
			'modified' => '2014-08-27 11:00:03'
		),
		array(
			'id' => 2,
			'title' => 'The title',
			'body' => 'This is the post body.',
			'created' => '2014-08-27 11:00:03',
			'modified' => '2014-08-27 11:00:03'
		),
		array(
			'id' => 3,
			'title' => 'A title once again',
			'body' => 'And the post body follows.',
			'created' => '2014-08-27 11:00:03',
			'modified' => '2014-08-27 11:00:03'
		),
		array(
			'id' => 4,
			'title' => 'Title strikes back',
			'body' => 'Title strikes back',
			'created' => '2014-08-27 11:00:03',
			'modified' => '2014-08-27 11:00:03'
		),
	);

}
