<?php
App::uses('AppModel', 'Model');

class Course extends AppModel {
	public $useTable = 'courses';
	public $primaryKey = 'course_id';
	public $displayField = 'name';
}
