

<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "editor/lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'lms_enrollees' )
	->where( 'lms_enrollees.course_id', $_POST['id'] )
	->fields(
		Field::inst( 'lms_enrollees.id' ),
		Field::inst( 'lms_enrollees.course_id' ),
		Field::inst( 'lms_enrollees.user_id' ),
		Field::inst( 'lms_enrollees.role_id' ),
		Field::inst( 'lms_courses.name' ),
		Field::inst( 'enrollment_list.firstname' ),
		Field::inst( 'enrollment_list.lastname' ),
		Field::inst( 'enrollment_list.middlename' )
	)
	->leftJoin( 'enrollment_list', 'lms_enrollees.user_id', '=', 'enrollment_list.id' )
	->leftJoin( 'lms_courses', 'lms_enrollees.course_id', '=', 'lms_courses.id' )
	->process( $_POST )
	->json();
