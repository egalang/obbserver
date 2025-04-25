<?php

include( "../editor/lib/DataTables.php" );

use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

Editor::inst( $db, 'scanlog' )
	->where( 'barcode', $_POST['barcode'] )
	->fields(
		Field::inst( 'id' ),
		Field::inst( 'logtype' ),
		Field::inst( 'barcode' ),
		Field::inst( 'date' )
	)
	->process( $_POST )
	->json();
