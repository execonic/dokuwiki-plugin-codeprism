<?php

class syntax_plugin_codeprism_codeprism extends DokuWiki_Syntax_Plugin {
	protected $entry_pattern = '<codeprism\b.*?>(?=.*?</codeprism>)';
	protected $exit_pattern = '</codeprism>';
	protected $match_pattern = '/<codeprism (.+?)( \[(.+?)\])* *>/';

	public function gettype() {
		return 'substition';
	}

	public function getSort() {
		return 157;
	}

	public function connectTo($mode) {
		$this->Lexer->addEntryPattern($this->entry_pattern, $mode, 'plugin_codeprism_codeprism');
	}

	public function postConnect() {
		$this->Lexer->addExitPattern($this->exit_pattern, 'plugin_codeprism_codeprism');
	}

	public function handle($match, $state, $pos, Doku_Handler $handler) {
		if ($state == DOKU_LEXER_ENTER) {
			/** <code php title start-line highlight [attr0="val0", attr1="val1", ...]> */

			//if (strpos("{$match}", '<codeprism') !== false) {
			preg_match_all($this->match_pattern, "{$match}", $chunks, PREG_SET_ORDER);

			if (!empty($chunks)) {
				//$opt_arr = array('data-start'=>'1', 'data-line-offset'=>'1');

				$chunk = $chunks[0];

				$basic_option = preg_split('/\s+/', trim($chunk[1]));

				/**chunk[1]: php title sl=* el=* ... */
				if (false == strpos($basic_option[0], '=')) {
					if ($basic_option[0] != '.') {
						$opt_arr['data-language'] = $basic_option[0];
					}

					unset($basic_option[0]);
				}

				if (false == strpos($basic_option[1], '=')) {
					if ($basic_option[1] != '.') {
						$opt_arr['title'] = $basic_option[1];
					}

					unset($basic_option[1]);
				}

//				preg_match_all('/([\w-]+?)=([\w-]*) */', "$basic_option", $basic_arr);

				foreach($basic_option as $opt) {
					$key_val = preg_split('/=/', $opt);

					switch ($key_val[0]) {
					case 'sl':
						$opt_arr['data-start'] = $key_val[1];
						$opt_arr['data-line-offset'] = $key_val[1];
						break;

					case 'hl':
						$opt_arr['data-line'] = $key_val[1];
						break;

					case 'el':
						$opt_arr['line-numbers'] = 'line-numbers';
						break;

					case 'lang':
						$opt_arr['data-language'] = $key_val[1];
						break;

					case 'cmd':
						$opt_arr['command-line'] = 'command-line';
						break;

					case 'cmdout':
						$opt_arr['data-output'] = $key_val[1];
						break;

					case 'user':
						$opt_arr['data-user'] = $key_val[1];
						break;

					case 'host':
						$opt_arr['data-host'] = $key_val[1];
						break;
					}
				}

				/**chunk[2]: [attr0="val0", attr1="val1", ...] */
				//$extend_option = preg_split('/\s*,\s*/', trim($chunk[2]));
				$extend_option = trim($chunk[2]);


				preg_match_all('/([\w-]+?)="(.*?)"/', "$extend_option", $extend_arr, PREG_SET_ORDER);


				foreach ($extend_arr as $key_val) {
					switch ($key_val[1]) {
					case 'start_line_numbers_at':
						$opt_arr['data-start'] = $key_val[2];
						$opt_arr['data-line-offset'] = $key_val[2];
						break;

					case 'enable_line_numbers':
						$opt_arr['line-numbers'] = 'line-numbers';
						break;

					case 'highlight_lines_extra':
						$opt_arr['data-line'] = $key_val[2];
						break;

					default:
						$opt_arr[$key_val[1]] = $key_val[2];
						break;
					}
				}
			}

			return array($state, $opt_arr);
		}

		return array($state, $match);
	}

	public function render($mode, Doku_Renderer $renderer, $data) {
		if ($mode != 'xhtml') return false;

		list($state) = $data;

		switch ($state) {
		case DOKU_LEXER_ENTER:
			list(, $opt_arr) = $data;

			$renderer->doc .= '<pre class="dokuwiki-plugin-codeprism ' . $opt_arr['line-numbers'] . $opt_arr['command-line'] . '"';
			unset($opt_arr['line-numbers']);
			unset($opt_arr['command-line']);

			foreach($opt_arr as $key => $val) {
				$renderer->doc .= ' ' . $key . '="' . $val . '"';
			}

			$renderer->doc .='>';
			$renderer->doc .='<code class="language-'. $opt_arr['data-language']  .'">';
			break;

		case DOKU_LEXER_UNMATCHED:
			list(, $code) = $data;
			$code = ltrim($code);
			$renderer->doc .= $renderer->_xmlEntities($code);
			break;

		case DOKU_LEXER_EXIT:
			$renderer->doc .= '</code>';
			$renderer->doc .= '</pre>';
			break;
		}

		return false;
	}
}

