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

Editor::inst( $db, 'assignment_types' )
	->where( 'assignment_types.course_id', $_POST['id'] )
	->fields(
		Field::inst( 'assignment_types.id' ),
		Field::inst( 'assignment_types.course_id' )
			->options( Options::inst()
				->table( 'lms_courses' )
				->value( 'id' )
				->label( array('code', 'name') )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'assignment_types.name' ),
		Field::inst( 'assignment_types.weight' ),
		Field::inst( 'lms_courses.code' ),
		Field::inst( 'lms_courses.name' )
	)
	->leftjoin( 'lms_courses', 'assignment_types.course_id', '=', 'lms_courses.id' )
	->process( $_POST )
	->json();
