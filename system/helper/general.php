<?php
function token($length = 32) {
	// Create random token
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$max = strlen($string) - 1;
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, $max)];
	}	
	
	return $token;
}

/**
 * Backwards support for timing safe hash string comparisons
 * 
 * http://php.net/manual/en/function.hash-equals.php
 */

if(!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if(strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}

function debuglog($something, $dump = false){
        
		$debug_log = new Log('debug.txt');
		if(!$dump){
        $debug_log->write($something);
        } else {
            $debug_log->write(var_export($something));
        }
	}
	
	if(!function_exists('make_keywords'))
{
	/**
    * returns a set of words ordered by occurrences
    *
    * $config = array(
    * 'limit_keywords_to' => integer,
    * 'avoid_words' => array(),
    * 'min_allowed_word_length' => integer,
    * 'use_utf8_decode' => Boolean,
    * 'order_by_occurence' => Boolean
    * );
    *
    * @param text $pContent - conteudo a transformar em keywords
    * @param array $config - (opcional) array contendo configuracoes
    * @return string - keywords
    **/
    function make_keywords( $pContent, $config = null )
    {
        # let's make an in_array case-insensitive
        if(!function_exists('in_arrayi')):
            function in_arrayi( $needle, $haystack ){
                return in_array( strtolower( $needle ), array_map( 'strtolower', $haystack ) );
            }
        endif;

        # initializing some default vars values
        $min_word_length = 4;
        # limit the number of output keywords
        $limit_keywords = 1000;
        # a set of disallowed words goes here
        $arr_avoid = array();
        # check if config param is set
        if(isset($config)):
            # looking for a minimum word length
            if(isset( $config['min_allowed_word_length'] )) 
                $min_word_length = $config['min_allowed_word_length'];
            # checking for a new limit
            if(isset( $config['limit_keywords_to'] )) 
                $limit_keywords = $config['limit_keywords_to'];
            # check if there's words to avoid
            if(isset( $config['avoid_words'] ) && ( is_array( $config['avoid_words'] ))) 
                $arr_avoid = $config['avoid_words'];
            # maybe the avoid_words is pointing to a file! lets check!
            if(isset( $config['avoid_words'] ) && ( is_string( $config['avoid_words']) )):
                if(file_exists( $config['avoid_words'] )){
                    # let's open the file in read mode
                    $file_in = fopen( $config['avoid_words'],"r" );
                    # let's get the filesize for the fread function
                    $size = filesize( $config['avoid_words'] ) + 1;
                    # getting the file content
                    $file_content = fread( $file_in, $size );
                    # avoid any tags
                    $file_content = strip_tags( $file_content );
                    # turn content into array
                    # remember that the words within the file must be separated by commas
                    $file_content = str_replace( " ", "", $file_content );
                    $arr_words = explode(",", $file_content);
                    if( is_array( $arr_words) )
                        $arr_avoid = $arr_words;
                }
            endif;
        endif;

        # sometimes need use utf8 decode to fix some character issues
        if( !isset( $config['use_utf8_decode'] ) ||
            (isset( $config['use_utf8_decode']) &&
                $config['use_utf8_decode'] == TRUE ) )
            $pContent = utf8_decode($pContent);

        $pContent = strip_tags($pContent);
        # preparando conteudo para conversao
        $content = str_replace( " ", "@", $pContent );
        # remove sinais de pontuacao
        $strip_arr = array( "," ,"." ,";" ,":", "\"", "'", "“","”","(",")", "!","?" );
        $content = str_replace( $strip_arr, "", $content );
        # separa palavras, e coloca as num array
        $arr_keywords = explode( "@", $content );
        # verifica tamanho da palavra para evita artigos, pronomes, etc
        # e tambem verifica se ha palavras nao desejadas
        $key = array();
        foreach ($arr_keywords as $value) :
            if( strlen( $value ) > $min_word_length ):
                # verifica por palavras nao desejadas
                if ( in_arrayi( $value, $arr_avoid ) ) continue;
                # se potencial keyword, entao, inclui ao array
                $keys[] = $value;
            endif;
        endforeach;
        # count the number of occurrences
        $tmp = array_count_values( $keys );
        # check if needs to order the array
        if(!isset($config['order_by_occurence'])
            || (isset($config['order_by_occurence'])
                && $config['order_by_occurence'] == TRUE ) )
            arsort( $tmp );
        # reduces the number of elements into the array
        $ordem = array_slice( $tmp, 0, $limit_keywords );
        $arr_keywords = array();
        # gets only the keys
        foreach($ordem as $key => $value ) 
            $arr_keywords[] = strtolower(trim( $key ));
        # implode elements with commas
        $keywords = implode( ', ', $arr_keywords );
        return $keywords;
    }
}