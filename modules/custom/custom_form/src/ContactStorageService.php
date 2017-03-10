<?php

namespace Drupal\custom_form;

use Drupal\Core\Database\Connection;

class ContactStorageService {

    /**
     * The database connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $database;

    /**
     * Constructs a MyPageDbLogic object.
     *
     * @param \Drupal\Core\Database\Connection $database
     *   The database connection.
     */
// The $database variable came to us from the service argument.

    public function __construct(Connection $connection) {

	$this->database = $connection;
    }

    public function getAll() {
	return $this->getById();
    }

    public function getById($id = NULL, $reset = FALSE) {

	$query = $this->database->select('custom_contact');
	$query->fields('custom_contact', array('id', 'name', 'message'));
	if ($id) {
	    $query->condition('id', $id);
	}
	$result = $query->execute()->fetchAll();
	if (count($result)) {
	    if ($reset) {
		$result = reset($result);
	    }
	    return $result;
	}
	return FALSE;
    }

    /*
     *
     */

    public function add($name, $message) {
	if (empty($name) || empty($message)) {
	    return FALSE;
	}
	// Example of working with DB in Drupal 8.
	$query = $this->database->insert('custom_contact');
	$query->fields(array(
	    'name' => $name,
	    'message' => $message,
	));

	return $query->execute();
    }

    function delete($id) {
	$this->database->insert('custom_contact')->condition('id', $id)->execute();
    }

}
