

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
Editor::inst( $db, 'tuition_matrix' )
	->fields(
		Field::inst( 'tuition_matrix.id' ),
//		Field::inst( 'tuition_matrix.level_id' ),
		Field::inst( 'tuition_matrix.level_id' )
				->options( Options::inst()
						->table( 'grade_levels' )
						->value( 'id' )
						->label( 'name' )
				)
		->validator( Validate::dbValues() ),
		Field::inst( 'grade_levels.name' ),
		Field::inst( 'tuition_matrix.term_id' )
				->options( Options::inst()
						->table( 'payment_terms' )
						->value( 'id' )
						->label( 'name' )
				)
		->validator( Validate::dbValues() ),
		Field::inst( 'payment_terms.name' ),
		Field::inst( 'tuition_matrix.tranche' ),
		Field::inst( 'tuition_matrix.amount' ),
		Field::inst( 'tuition_matrix.date' )
//			->ValidateOptions::inst()
//		->validator( Validate::dateFormat() )
//		->getFormatter( Format::dateSqlToFormat() )
//		->setFormatter( Format::dateFormatToSql() )
	)
	->leftJoin( 'grade_levels', 'tuition_matrix.level_id', '=', 'grade_levels.id' )
	->leftJoin( 'payment_terms', 'tuition_matrix.term_id', '=', 'payment_terms.id' )
	->process( $_POST )
	->json();
