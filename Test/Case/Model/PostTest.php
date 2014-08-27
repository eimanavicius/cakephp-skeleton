<?php
App::uses('Post', 'Model');

/**
 * Post Test Case
 *
 * @property Post $Post
 */
class PostTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.post'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Post = ClassRegistry::init('Post');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Post);

		parent::tearDown();
	}

    public function testFindAll() {
        $posts = $this->Post->find();
        $this->assertNotEmpty($posts);
    }

    /**
     * @dataProvider validationDataProvider
     *
     * @param $data
     * @param $result
     * @param $options
     */
    public function testValidation($data, $result, $options) {
        $this->Post->set($data);
        $this->assertEquals($result, $this->Post->validates($options));
    }

    public function validationDataProvider() {
        $validateAll = array();
        $validateOnlyTitle = array('fieldList' => array('title'));
        $validateOnlyBody = array('fieldList' => array('body'));
        return array(
            'empty data set' => array(
                array(),
                false,
                $validateAll,
            ),
            'empty title' => array(
                array(
                    'title' => '',
                ),
                false,
                $validateOnlyTitle,
            ),
            'filled title shorter than 2' => array(
                array(
                    'title' => 'T',
                ),
                false,
                $validateOnlyTitle,
            ),
            'filled title longer than 2' => array(
                array(
                    'title' => 'The title',
                ),
                true,
                $validateOnlyTitle
            ),
            'filled title longer than 50' => array(
                array(
                    'title' => 'The title title title title title title title title',
                ),
                false,
                $validateOnlyTitle,
            ),
            'body is required' => array(
                array(),
                false,
                $validateOnlyBody,
            ),
            'body must be not empty' => array(
                array(
                    'body' => '',
                ),
                false,
                $validateOnlyBody,
            ),
            'body is not empty' => array(
                array(
                    'body' => 'H',
                ),
                true,
                $validateOnlyBody,
            ),
            'all fields invalid' => array(
                array(
                    'title' => 'H',
                    'body' => '',
                ),
                false,
                $validateAll,
            ),
            'all fields valid' => array(
                array(
                    'title' => 'The title',
                    'body' => 'The body text',
                ),
                true,
                $validateAll,
            ),
        );
    }
}
