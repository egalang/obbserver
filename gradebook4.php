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

Editor::inst( $db, 'gradebook' )
	->where( 'gradebook.assignment_id', $_POST['id'] )
	->fields(
		Field::inst( 'gradebook.id' ),
		Field::inst( 'gradebook.assignment_id' ),
		Field::inst( 'gradebook.student_id' ),
		Field::inst( 'gradebook.grade' ),
		Field::inst( 'enrollment_list.lastname' ),
		Field::inst( 'enrollment_list.firstname' ),
		Field::inst( 'enrollment_list.middlename' )
	)
	->leftJoin( 'enrollment_list', 'gradebook.student_id', '=', 'enrollment_list.id' )
	->process( $_POST )
	->json();
