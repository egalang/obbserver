<?php

include( "editor/lib/DataTables.php" );

use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

Editor::inst( $db, 'lms_courses' )
	->fields(
		Field::inst( 'lms_courses.id' ),
		Field::inst( 'lms_courses.code' ),
		Field::inst( 'lms_courses.name' ),
		Field::inst( 'lms_courses.level_id' )
			->options( Options::inst()
				->table( 'grade_levels' )
				->value( 'id' )
				->label( 'name' )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'lms_courses.section_id' )
			->options( Options::inst()
				->table( 'sections' )
				->value( 'id' )
				->label( 'name' )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'lms_courses.teacher_id' )
			->options( Options::inst()
				->table( 'teachers' )
				->value( 'id' )
				->label( array('firstname', 'lastname') )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'grade_levels.name' ),
		Field::inst( 'sections.name' ),
		Field::inst( 'teachers.lastname' ),
		Field::inst( 'teachers.firstname' ),
		Field::inst( 'teachers.middlename' ),
		Field::inst( 'lms_courses.sort_order' )
	)
	->leftjoin( 'grade_levels', 'lms_courses.level_id', '=', 'grade_levels.id' )
	->leftjoin( 'sections', 'lms_courses.section_id', '=', 'sections.id' )
	->leftjoin( 'teachers', 'lms_courses.teacher_id', '=', 'teachers.id' )
	->process( $_POST )
	->json();
