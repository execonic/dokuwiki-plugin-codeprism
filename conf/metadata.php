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
		'https://unpkg.com/prismjs@1.29.0/',
	]
];

$meta['override-code'] = [
	'onoff',
];

$meta['show-invis'] = [
	'onoff',
];

$meta['hl-brace'] = [
	'onoff',
];

$meta['previewer'] = [
	'onoff',
];

$meta['user']  = array('string');
$meta['host']  = array('string');
