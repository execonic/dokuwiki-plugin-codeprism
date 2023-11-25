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
		'https://cdn.bootcdn.net/ajax/libs/prism/1.29.0/',
	]
];

$meta['override-code'] = array('onoff');
$meta['show-invis'] = array('onoff');
$meta['hl-brace'] = array('onoff');
$meta['previewer'] = array('onoff');
$meta['user']  = array('string');
$meta['host']  = array('string');
