<?php

class CakephpblogSchema extends CakeSchema {

	public function after($event = array()) {
        if (isset($event['create'])) {
            switch ($event['create']) {
                case 'posts':
                    /** @var Model $post */
                    $post = ClassRegistry::init('Post');
                    $post->create();
                    $post->saveAll(array(
                        array('Post' =>
                            array('title' => 'The title', 'body' => 'This is the post body.')
                        ),
                        array('Post' =>
                            array('title' => 'A title once again', 'body' => 'And the post body follows.')
                        ),
                        array('Post' =>
                            array('title' => 'Title strikes back', 'body' => 'This is really exciting! Not.')
                        ),
                    ));
                    break;
            }
        }
	}

	public $posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 50),
		'body' => array('type' => 'text', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(

		),
		'tableParameters' => array()
	);

}
