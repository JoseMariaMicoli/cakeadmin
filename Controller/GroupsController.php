<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 * Groups Controller
 *
 */
class GroupsController extends AdminAppController {

	public $components = array('Paginator', 'Session', 'Auth', 'Acl');

	public function view($id = null)
	{
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
		$this->set('group', $this->Group->find('first', $options));
	}

	public function index()
	{
		$this->Group->recursive = 0;
		$this->set('groups', $this->Paginator->paginate());
	} 

	public function add()
	{
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		}
		$groups = $this->Group->User->find('list');
		$this->set(compact('users'));
	}

	public function edit($id = null)
	{
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
		$groups = $this->Group->User->find('list');
		$this->set(compact('users'));
	}

	public function delete($id = null)
	{
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The group has been deleted.'));
		} else {
			$this->Session->setFlash(__('The group could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function beforeFilter()
	{
    	parent::beforeFilter();

    	// For CakePHP 2.1 and up
    	$this->Auth->allow('view');
	}
}
