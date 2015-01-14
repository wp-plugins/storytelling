<?php
// ******************************************************
//
// Remover des contenus
//
// ******************************************************

class Storytelling__utility
{

    // ============================================================
    // Load Templates
    // ============================================================

	public function get_file_data( $file ) {
		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $file, 'r' );

		// Pull only the first 8kiB of the file in.
		$file_data = fread( $fp, 8192 );

		// PHP will close file handle, but we are good citizens.
		fclose( $fp );

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );

		// log_it( $file_data );
		// preg_match("({.*})",$file_data,$m);
		// $string = trim(preg_replace('/\s\s+/', ' ', $file_data));
		$string = trim(preg_replace('/\s+/', ' ', $file_data));
		// $string = preg_replace('\<?php','', $file_data);

		// log_it($string);
		// $res = preg_match('{(.*)}', $string);

		$jsons = [];

		$res = preg_match_all('~\{(?:[^{}]|(?R))*\}~', $string, $matches);
		// log_it($macthes[0]);
		if( !empty($matches[0]) ){
			// log_it($matches);

			foreach ($matches[0] as $key => $res){
				$jsons[] = $res;
			}

			return $jsons;
		}

	}

}