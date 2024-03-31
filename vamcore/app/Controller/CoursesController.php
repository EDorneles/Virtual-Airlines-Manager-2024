<?php
App::uses('AppController', 'Controller');
/**
 * courses Controller
 *
 * @property course $course
 */
class CoursesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->course->recursive = 0;
		$this->set('courses', $this->paginate());
	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->course->exists($id)) {
			throw new NotFoundException(__('Invalid course'));
		}
		$options = array('conditions' => array('course.' . $this->course->primaryKey => $id));
		$this->set('course', $this->course->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->course->create();
			if ($this->course->save($this->request->data)) {
				$this->Session->setFlash(__('The course has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The course could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->course->exists($id)) {
			throw new NotFoundException(__('Invalid course'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->course->save($this->request->data)) {
				$this->Session->setFlash(__('The course has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The course could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('course.' . $this->course->primaryKey => $id));
			$this->request->data = $this->course->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->course->id = $id;
		if (!$this->course->exists()) {
			throw new NotFoundException(__('Invalid course'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->course->delete()) {
			$this->Session->setFlash(__('course deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('course was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
