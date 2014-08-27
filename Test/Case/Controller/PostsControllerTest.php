<?php
App::uses('PostsController', 'Controller');

/**
 * PostsController Test Case
 *
 */
class PostsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.post'
	);

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
        $result = $this->testAction('/posts/index', array('return' => 'vars'));
        $this->assertArrayHasKey('posts', $result);
        $this->assertCount(4, $result['posts']);
	}

/**
 * testView method
 *
 * @expectedException NotFoundException
 * @return void
 */
	public function testViewNotExistingPost() {
        $this->testAction('/posts/view/5');
	}

/**
 * testView method
 *
 * @return void
 */
    public function testViewPost() {
        $result = $this->testAction('/posts/view/3', array('return' => 'vars'));
        $this->assertArrayHasKey('post', $result);
        $this->assertArrayHasKey('Post', $result['post']);
        unset($result['post']['Post']['created']);
        unset($result['post']['Post']['modified']);
        $this->assertEquals(
            array(
                'id' => '3',
                'title' => 'A title once again',
                'body' => 'And the post body follows.'
            ),
            $result['post']['Post']
        );
    }

/**
 * testAdd method
 *
 * @return void
 */
	public function testAddWithGetRequest() {
        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('create')
                ),
            ));

        $Posts->Post
            ->expects($this->never())
            ->method('create');

        $this->testAction('/posts/add', array('method' => 'get'));
	}

/**
 * testAdd method
 *
 * @return void
 */
    public function testAddEmptyPostRequest() {
        $data = array();

        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('create', 'save')
                ),
                'components' => array(
                    'Session',
                )
            ));

        $Posts->Post
            ->expects($this->once())
            ->method('create')
            ->with(array(), false);
        $Posts->Post
            ->expects($this->once())
            ->method('save')
            ->with($data)
            ->will($this->returnValue(false));
        $Posts->Session
            ->expects($this->once())
            ->method('setFlash');

        $this->testAction(
            '/posts/add',
            array('data' => $data, 'method' => 'post')
        );

        $this->assertArrayNotHasKey('Location', $this->headers);
    }

/**
 * testAdd method
 *
 * @return void
 */
    public function testAddPostRequest() {
        $data = array(
            'Post' => array(
                'title' => 'New Article',
                'body' => 'New Body'
            )
        );

        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('create', 'save')
                ),
                'components' => array(
                    'Session',
                )
            ));

        $Posts->Post
            ->expects($this->once())
            ->method('create')
            ->with(array(), false);
        $Posts->Post
            ->expects($this->once())
            ->method('save')
            ->with($data)
            ->will($this->returnValue(true));
        $Posts->Session
            ->expects($this->once())
            ->method('setFlash');

        $this->testAction(
            '/posts/add',
            array('data' => $data, 'method' => 'post')
        );

        $this->assertContains('/posts', $this->headers['Location']);
    }

/**
 * testEdit method
 *
 * @expectedException NotFoundException
 *
 * @return void
 */
    public function testEditNotExistingPost() {
        $Posts = $this->generate(
            'Posts',
            array(
                'models' => array(
                    'Post' => array('exists')
                ),
            )
        );
        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with(5)
            ->will($this->returnValue(false));

        $this->testAction('/posts/edit/5');
    }

/**
 * testEdit method
 *
 * @return void
 */
    public function testEditForm() {
        $id = '4';
        $options = array('conditions' => array('Post.id' => $id));
        $post = array(
            'Post' => array(
                'id' => $id,
                'title' => 'This is title',
                'body' => 'This is body text.',
                'created' => '2014-07-28 12:52:32',
                'modified' => '2014-07-28 12:52:32',
            )
        );

        $Posts = $this->generate(
            'Posts',
            array(
                'models' => array(
                    'Post' => array('find', 'exists')
                ),
            )
        );
        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with($id)
            ->will($this->returnValue(true));
        $Posts->Post
            ->expects($this->once())
            ->method('find')
            ->with('first', $options)
            ->will($this->returnValue($post));

        $this->testAction('/posts/edit/' . $id, array('method' => 'get'));

        $this->assertSame($post, $this->controller->request->data);
    }

/**
 * testEdit method
 *
 * @dataProvider postEditDataProvider
 *
 * @param string $method request method
 * @return void
 */
	public function testEditPostInvalid($method) {
        $id = '4';
        $data = array();

        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('save', 'exists')
                ),
                'components' => array(
                    'Session',
                )
            ));
        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with($id)
            ->will($this->returnValue(true));
        $Posts->Post
            ->expects($this->once())
            ->method('save')
            ->with($data)
            ->will($this->returnValue(false));
        $Posts->Session
            ->expects($this->once())
            ->method('setFlash');

        $this->testAction(
            '/posts/edit/' . $id,
            array('data' => $data, 'method' => $method)
        );

        $this->assertArrayNotHasKey('Location', $this->headers);
    }

/**
 * testEdit method
 *
 * @dataProvider postEditDataProvider
 *
 * @param string $method request method
 * @return void
 */
	public function testEditSavesPostAndRedirects($method) {
        $id = '4';
        $data = array(
            'Post' => array(
                'id' => $id,
                'title' => 'This is title',
                'body' => 'This is body text.',
                'created' => '2014-07-28 12:52:32',
                'modified' => '2014-07-28 12:52:32',
            )
        );

        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('save', 'exists')
                ),
                'components' => array(
                    'Session',
                )
            ));
        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with($id)
            ->will($this->returnValue(true));
        $Posts->Post
            ->expects($this->once())
            ->method('save')
            ->with($data)
            ->will($this->returnValue(true));
        $Posts->Session
            ->expects($this->once())
            ->method('setFlash');

        $this->testAction(
            '/posts/edit/' . $id,
            array('data' => $data, 'method' => $method)
        );

        $this->assertContains('/posts', $this->headers['Location']);
	}

/**
 * @return array
 */
    public function postEditDataProvider() {
        return array(
            'put method' => array('put'),
            'post method' => array('post'),
        );
    }

/**
 * testDelete method
 *
 * @dataProvider deleteDataProvider
 *
 * @param string $method
 * @param boolean $deleteSuccess
 * @return void
 */
	public function testDeleteActionWithPostDeleteMethods($method, $deleteSuccess) {
        $id = '4';

        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('exists', 'delete')
                ),
                'components' => array(
                    'Session' => array('setFlash')
                ),
            ));

        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with($this->anything())
            ->will($this->returnValue(true));
        $Posts->Post
            ->expects($this->once())
            ->method('delete')
            ->will($this->returnValue($deleteSuccess));
        $Posts->Session
            ->expects($this->once())
            ->method('setFlash');

        $this->testAction(
            '/posts/delete/' . $id,
            array('method' => $method)
        );
	}

    /**
     * @return array
     */
    public function deleteDataProvider() {
        return array(
            'delete method, delete success' => array('delete', true),
            'delete method, delete failed' => array('delete', false),
            'post method, delete success' => array('post', true),
            'post method, delete failed' => array('post', false),
        );
    }

    /**
     * testDelete method
     *
     * @dataProvider deleteDataProviderOtherThanPostDeleteMethods
     * @expectedException MethodNotAllowedException
     * @expectedExceptionMessage Method Not Allowed
     *
     * @param string $method
     * @return void
     */
    public function testDeleteActionAllowsDenyOtherThanPostDeleteMethods($method) {
        $id = '4';

        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('exists')
                ),
            ));

        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with($this->anything())
            ->will($this->returnValue(true));

        $this->testAction(
            '/posts/delete/' . $id,
            array('method' => $method)
        );
    }

    /**
     * @return array
     */
    public function deleteDataProviderOtherThanPostDeleteMethods() {
        return array(
            'get method' => array('get'),
            'patch method' => array('patch'),
            'put method' => array('put'),
        );
    }

    /**
     * @expectedException NotFoundException
     */
    public function testDeleteActionCheckPostExists() {
        $Posts = $this->generate('Posts', array(
                'models' => array(
                    'Post' => array('exists')
                ),
            ));

        $Posts->Post
            ->expects($this->any())
            ->method('exists')
            ->with($this->anything())
            ->will($this->returnValue(false));

        $this->testAction(
            '/posts/delete/5',
            array('method' => 'delete')
        );
    }

}
