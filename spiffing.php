<?php
	class spiffing {
		/*
		 * The dictionary which has been made into a more accessible array.
		 *
		 * @var		array
		 * @access	public
		 */
		public $dictionary 		= array(
			// Queen's English	// Primitive English from our stateside friends from across the pond.
			'colour'			=> 'color',
			'grey'				=> 'gray',
			'!please'			=> '!important',
			'transparency'		=> 'opacity',
			'centre'			=> 'center',
			'plump'				=> 'bold',
			'photograph'		=> 'image',
			'capitalise'		=> 'capitalize'
		);
		/*
		 * The URL hook
		 *
		 * @var		string
		 * @access	private
		 */
		private $hook			= 'spiffing=';
		/*
		 * The file variable
		 *
		 * @var		string
		 * @access	public
		 */
		public $file;
		/*
		 * Perfectly British CSS right here
		 *
		 * @var 	string
		 * @access 	public
		 */
		public $css;
		/*
		 * Constructor
		 *
		 * @param 	string
		 */
		function __construct($raw = '') {
			if ( !empty( $raw )) {
				$this->css = $raw;
			} else if ( isset( $_SERVER ) ) {
				// Santise the string.
				$this->file 	= dirname( __FILE__ )
								. '/' . str_replace( $this->hook, '',
									filter_input( INPUT_GET, 'file', FILTER_SANITIZE_STRING ) );
				if( !file_exists( $this->file ) or !is_readable( $file ) ) {
					$this->not_found();
				}
				$this->css = file_get_contents( $this->file );
			}
			$this->process();
		}
		/*
		 * Magic really.
		 *
		 * @param	void
		 * @access 	public
		 */
		public function process() {
			// The finished CSS.
			$processed			= '';
			// The array which will hold all found CSS attributes to be repalced.
			$replacements		= array();
			// The magic pattern which finds ONLY attributes.
			$pattern			= '/(?:(?:\s|\t)*|\;)([\w-]*):/i';
			// One should begin by searching the CSS for exlusive Britishness.
			preg_match_all( $pattern, $this->css, $matches );
			foreach( $matches[1] as $index => $value ) {
				// Let's run through the Queen's dictionary for every found term.
				foreach( $this->dictionary as $british => $primitive ) {
					// Did we find some Britishness?
					if( strpos( $value, $british ) !== FALSE ) {
						// We don't want overlapse - now we do not have an as big foreach to get through.
						$replacements[$value] = str_ireplace($british, $primitive, $value);
					}
				}
			}
			// Now that we have the attributes to replace, let us begin...
			foreach( $replacements as $search => $replace ) {
				$processed = str_ireplace( $search, $replace, $this->css );
			}
			// Set the CSS header.
			$this->set_header('Content-Type: text/css');
			echo $processed;
			exit;
		}
		/*
		 * Set a custom header
		 *
		 * @param	string
		 * @access 	private
		 */
		private function set_header($header) {
			//  Safely set the header
			if( !headers_sent() ) {
				header( $header );
			}
		}
		/*
		 * Create a 404 function, since we do that a lot.
		 *
		 * @param	void
		 * @access 	private
		 */
		private function not_found() {
			//  Set the header
			set_header('HTTP/1.0 404 Not Found');
			//  And stop execution. We don't need no content.
			exit;
		}
		/*
		 * Deconstructor
		 */
		function __deconstruct() {
			$this->not_found();
		}
	}
?>