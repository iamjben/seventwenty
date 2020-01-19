<?php
// String to URL - convert to seo friendly url
function str_slug($string, $separator = '-') {
	$accents = array('Š' => 'S', 'š' => 's', 'Ð' => 'Dj','Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f');
	$string = strtr($string, $accents);
	$string = strtolower($string);
	$string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
	$string = preg_replace('{ +}', ' ', $string);
	$string = trim($string);
	$string = str_replace(' ', $separator, $string);

	return strtolower($string);
}

// Get URL Segment
function url_segment($segment = false) {
	if ($segment == false) return false;
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);
	return $uri_segments[$segment];
}

// Excerpt - Generate excerpt
function excerpt($string, $length = 10, $trailing='...') {
	$length -= mb_strlen($trailing);
	if (mb_strlen($string) > $length) return mb_substr($string, 0, $length).$trailing;
	return $string;
}

// CPT Pagination - Custom Pagination for custom post type(s)
function cpt_pagination($pages = '', $range = 10) {
	global $paged;
	$showitems = ($range * 2)+1;
	if (empty($paged)) $paged = 1;

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}

	if (1 != $pages) {
		echo '<ul class="pagination">';
		if ($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<li><a href="'.get_pagenum_link(1).'">&laquo; First</a></li>';
		if ($paged > 1 && $showitems < $pages) echo '<li><a href="'.get_pagenum_link($paged - 1).'">&lsaquo; Previous</a></li>';
		for ($i=1; $i <= $pages; $i++) {
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
				echo ($paged == $i)? '<li class="active"><span class="current">'.$i.'</span></li>' : '<li><a href="'.get_pagenum_link($i).'" class="inactive">'.$i.'</a></li>';
			}
		}
		if ($paged < $pages && $showitems < $pages) echo '<li><a href="'.get_pagenum_link($paged + 1).'">Next &rsaquo;</a></li>';
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<li><a href="'.get_pagenum_link($pages).'">Last &raquo;</a></li>';
		echo '</ul>';
	}
}

// Print Array - for debugging
function dd($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}