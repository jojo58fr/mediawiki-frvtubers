<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\OutputTransformStageTest;
use MediaWiki\OutputTransform\TestUtils;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \MediaWiki\OutputTransform\Stages\DeduplicateStyles
 */
class DeduplicateStylesTest extends OutputTransformStageTest {

	public function createStage(): OutputTransformStage {
		return new DeduplicateStyles();
	}

	public function provideShouldRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'deduplicateStyles' => true ] ],
			[ new ParserOutput(), null, [] ],
		] );
	}

	public function provideShouldNotRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'deduplicateStyles' => false ] ],
		] );
	}

	public function provideTransform(): array {
		$dedup = <<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate2">
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF;

		$po = new ParserOutput( TestUtils::TEST_TO_DEDUP );
		$expected = new ParserOutput( $dedup );
		$opts = [];
		return [
			[ $po, null, $opts, $expected ]
		];
	}
}
