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

Editor::inst( $db, 'assignments' )
	->where( 'assignments.type_id', $_POST['id'] )
	->where( 'assignments.period_id', $_POST['pid'] )
	->fields(
		Field::inst( 'id' ),
		Field::inst( 'type_id' ),
		Field::inst( 'period_id' ),
		Field::inst( 'lms_id' ),
		Field::inst( 'name' ),
		Field::inst( 'description' ),
		Field::inst( 'top_score' )
	)
	->process( $_POST )
	->json();
