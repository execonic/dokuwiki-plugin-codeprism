<?php

class syntax_plugin_codePrism extends DokuWiki_Syntax_Plugin {
	public function gettype() {
		return 'substition';
	}

	public function getSort() {
		return 158;
	}

	public function connectTo($mode) {
		$this->Lexer->addEntryPattern('<code.*?>(?=.*?</code>)', $mode, 'plugin_codePrism');
	}

	public function postConnect() {
		$this->Lexer->addExitPattern('</code>', 'plugin_codePrism');
	}

	public function handle($match, $state, $pos, Doku_Handler $handler) {
	if ($state == DOKU_LEXER_ENTER) {
		preg_match_all('/<code (.+?) \[(.+?)\]', $match, $chunks);

		if (!empty($chunks)) {
			$chunk = $chunks[0];
			/** <code php title start-line highlight [attr0="val0", attr1="val1", ...]> */
			$basic_option = preg_split('/\s+/', trim($chunk[1]));

			$opt_arr['lang'] = $basic_option[0];
			$opt_arr['title'] = $basic_option[1];
			$opt_arr['start-line'] = $basic_option[2];
			$opt_arr['highlight'] = $basic_option[3];
			sl=
			hl=
			el=

			$extend_option = preg_split('/\s*,\s*/,' trim($chunk[2]));

			preg_match_all('/([\w-]+?)="([\w-]*)" *,/', $extend_option, $extend_arr);

			foreach ($extend_arr as $key_val) {
				switch ($key_val[1]) {
				case 'start_line_numbers_at':
					$opt_arr['start-line'] = $key_val[2];
					break;
				case 'enable_line_numbers':
					$opt_arr['']
				}


			}
		}

		$chunk = preg_split("/\s+/", $attributes);
		$match = $chunk[0];
	}

	return array($state, $match);
  }

}