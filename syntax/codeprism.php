<?php

class syntax_plugin_codeprism_codeprism extends DokuWiki_Syntax_Plugin
{
	protected $entry_pattern = '<codeprism\b.*?>(?=.*?</codeprism>)';
	protected $exit_pattern = '</codeprism>';
	protected $match_pattern = '/<codeprism (.+?)( \[(.+?)\])* *>/';

	public function gettype()
	{
		return 'substition';
	}

	public function getSort()
	{
		return 190;
	}

	public function connectTo($mode)
	{
		$this->Lexer->addEntryPattern($this->entry_pattern, $mode, 'plugin_codeprism_codeprism');
	}

	public function postConnect()
	{
		$this->Lexer->addExitPattern($this->exit_pattern, 'plugin_codeprism_codeprism');
	}

	public function handle($match, $state, $pos, Doku_Handler $handler)
	{
		if (isset($_REQUEST['comment'])) return false;

		$pre_opt_arr['css'] = 'dft';

		if ($state == DOKU_LEXER_ENTER) {
			/** Default attributes. */
			if ($this->getConf('hl-brace')) {
				$code_opt_arr['match-braces'] = 'match-braces';
			}

			if ($this->getConf('user')) {
				$pre_opt_arr['data-user'] = $this->getConf('user');
			}

			if ($this->getConf('host')) {
				$pre_opt_arr['data-host'] = $this->getConf('host');
			}

			preg_match_all($this->match_pattern, "{$match}", $chunks, PREG_SET_ORDER);

			if (!empty($chunks)) {
				$chunk = $chunks[0];

				$basic_option = preg_split('/\s+/', trim($chunk[1]));

				/**chunk[1]: php title sl=* el=* ... */
				if (false == strpos($basic_option[0], '=')) {
					if ($basic_option[0] != '.') {
						$pre_opt_arr['data-language'] = $basic_option[0];
						$code_opt_arr['lang'] = $basic_option[0];
					}

					unset($basic_option[0]);
				}

				if (false == strpos($basic_option[1], '=')) {
					if ($basic_option[1] != '.') {
						$pre_opt_arr['title'] = $basic_option[1];
					}

					unset($basic_option[1]);
				}

				foreach($basic_option as $opt) {
					$key_val = preg_split('/=/', $opt);

					switch ($key_val[0]) {
					/** Parse <codeprism> syntax. */
					case 'sl':
						$pre_opt_arr['data-start'] = $key_val[1];
						$pre_opt_arr['data-line-offset'] = $key_val[1];
						break;

					case 'hl':
						$pre_opt_arr['data-line'] = $key_val[1];
						break;

					case 'el':
						$pre_opt_arr['line-numbers'] = 'line-numbers';
						break;

					case 'lang':
						$pre_opt_arr['data-language'] = $key_val[1];
						$code_opt_arr['lang'] = $key_val[1];
						break;

					case 'cmd':
						$pre_opt_arr['command-line'] = 'command-line';
						break;

					case 'cmdout':
						$pre_opt_arr['data-output'] = $key_val[1];
						$pre_opt_arr['command-line'] = 'command-line';
						break;

					case 'user':
						$pre_opt_arr['data-user'] = $key_val[1];
						break;

					case 'host':
						$pre_opt_arr['data-host'] = $key_val[1];
						break;

					case 'css':
						$pre_opt_arr['css'] = $key_val[1];
						break;

					/** Parse <fileprism> syntax. */
					case 'src':
						$pre_opt_arr['data-src'] = DOKU_BASE. 'lib/exe/fetch.php?media=' . $key_val[1];
						break;

					case 'range':
						$pre_opt_arr['data-range'] = $key_val[1];
						break;
					}
				}

				/**chunk[2]: [attr0="val0", attr1="val1", ...] */
				$extend_option = trim($chunk[2]);

				preg_match_all('/([\w-]+?)="(.*?)"/', "$extend_option", $extend_arr, PREG_SET_ORDER);

				foreach ($extend_arr as $key_val) {
					switch ($key_val[1]) {
					case 'start_line_numbers_at':
						$pre_opt_arr['data-start'] = $key_val[2];
						$pre_opt_arr['data-line-offset'] = $key_val[2];
						break;

					case 'enable_line_numbers':
						$pre_opt_arr['line-numbers'] = 'line-numbers';
						break;

					case 'highlight_lines_extra':
						$pre_opt_arr['data-line'] = $key_val[2];
						break;

					default:
						$pre_opt_arr[$key_val[1]] = $key_val[2];
						break;
					}
				}
			}

			/* `data-user` & `data-host` should be only used in command line. */
			if (!$pre_opt_arr['command-line']) {
				unset($pre_opt_arr['data-user']);
				unset($pre_opt_arr['data-host']);
			}

			return array($state, $pre_opt_arr, $code_opt_arr);
		}

		return array($state, $match);
	}

	public function render($mode, Doku_Renderer $renderer, $data)
	{
		if ($mode != 'xhtml') return false;

		list($state) = $data;

		switch ($state) {
		case DOKU_LEXER_ENTER:
			list(, $pre_opt_arr, $code_opt_arr) = $data;

			$renderer->doc .= '<pre class="dokuwiki-plugin-codeprism-'.$pre_opt_arr['css'].' '.$pre_opt_arr['line-numbers'].' '.$pre_opt_arr['command-line'] . '"';
			unset($pre_opt_arr['line-numbers']);
			unset($pre_opt_arr['command-line']);

			foreach($pre_opt_arr as $key => $val) {
				$renderer->doc .= ' ' . $key . '="' . $val . '"';
			}

			$renderer->doc .='>';
			$renderer->doc .='<code class="language-'. $code_opt_arr['lang'] . ' ' . $code_opt_arr['match-braces'] . '">';
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
