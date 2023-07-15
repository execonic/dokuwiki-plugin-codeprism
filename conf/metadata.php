<?php

$meta['theme'] = [
	'multichoice',
	'_choices' => [
		'default',
		'coy',
		'dark',
		'funky',
		'okaidia',
		'solarizedlight',
		'tomorrow',
		'twilight',
	]
];

$meta['cdn'] = [
	'multichoice',
	'_choices' => [
		'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/',
		'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/',
		'https://unpkg.com/browse/prismjs@1.29.0/',
	]
];

$meta['override-code'] = [
	'multichoice',
	'_choices' => [
		'true',
		'false',
	]
];

$meta['show-invis'] = [
	'multichoice',
	'_choices' => [
		'true',
		'false',
	]
];

