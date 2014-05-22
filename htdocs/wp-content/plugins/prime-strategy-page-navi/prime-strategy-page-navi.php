<?php
/*
Plugin Name: Prime Strategy Page Navi
Plugin URI: http://www.warna.info/archives/1328/
Description: Adds the functions to display page navigation (pagination) on home and archives (categories, authors, date and so on).
Author: Hitoshi Omagari
Version: 1.0.2
Author URI: http://www.warna.info/
 License: GPLv2 or later
 PHP Version: Require PHP5
*/
class prime_strategy_page_navi {
public function page_navi( $args = '' ) {
	global $wp_query;

	if ( ! ( is_archive() || is_home() || is_search() ) ) { return; }
	$default = array(
		'items'				=> 11,
		'edge_type'			=> 'none',
		'show_adjacent'		=> true,
		'prev_label'		=> '&lt;',
		'next_label'		=> '&gt;',
		'show_boundary'		=> true,
		'first_label'		=> '&laquo;',
		'last_label'		=> '&raquo;',
		'show_num'			=> false,
		'num_position'		=> 'before',
		'num_format'		=> '<span>%d/%d</span>',
		'echo'				=> true,
		'navi_element'		=> '',
		'elm_class'			=> 'page_navi',
		'elm_id'			=> '',
		'li_class'			=> '',
		'current_class'		=> 'current',
		'current_format'	=> '<span>%d</span>',
		'class_prefix'		=> '',
		'indent'			=> 0
	);
	$default = apply_filters( 'page_navi_default', $default );

	$args = wp_parse_args( $args, $default );

	$max_page_num = $wp_query->max_num_pages;
	$current_page_num = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$elm = in_array( $args['navi_element'], array( 'nav', 'div', '' ) ) ? $args['navi_element'] : 'div';
 
	$args['items'] = absint( $args['items'] ) ? absint( $args['items'] ) : $default['items'];
	$args['elm_id'] = is_array( $args['elm_id'] ) ? $default['elm_id'] : $args['elm_id'];
	$args['elm_id'] = preg_replace( '/[^\w_-]+/', '', $args['elm_id'] );
	$args['elm_id'] = preg_replace( '/^[\d_-]+/', '', $args['elm_id'] );
 
	$args['class_prefix'] = is_array( $args['class_prefix'] ) ? $default['class_prefix'] : $args['class_prefix'];
	$args['class_prefix'] = preg_replace( '/[^\w_-]+/', '', $args['class_prefix'] );
	$args['class_prefix'] = preg_replace( '/^[\d_-]+/', '', $args['class_prefix'] );
 
	$args['elm_class'] = $this->sanitize_attr_classes( $args['elm_class'], $args['class_prefix'] );
	$args['li_class'] = $this->sanitize_attr_classes( $args['li_class'], $args['class_prefix'] );
	$args['current_class'] = $this->sanitize_attr_classes( $args['current_class'], $args['class_prefix'] );
	$args['current_class'] = $args['current_class'] ? $args['current_class'] : $default['current_class'];
	$args['show_adjacent'] = $this->uniform_boolean( $args['show_adjacent'], $default['show_adjacent'] );
	$args['show_boundary'] = $this->uniform_boolean( $args['show_boundary'], $default['show_boundary'] );
	$args['show_num'] = $this->uniform_boolean( $args['show_num'], $default['show_num'] );
	$args['echo'] = $this->uniform_boolean( $args['echo'], $default['echo'] );

	$tabs = str_repeat( "\t", (int)$args['indent'] );
	$elm_tabs = '';

	$befores = $current_page_num - floor( ( $args['items'] - 1 ) / 2 );
	$afters = $current_page_num + ceil( ( $args['items'] - 1 ) / 2 );

	if ( $max_page_num <= $args['items'] ) {
		$start = 1;
		$end = $max_page_num;
	} elseif ( $befores <= 1 ) {
		$start = 1;
		$end = $args['items'];
	} elseif ( $afters >= $max_page_num ) {
		$start = $max_page_num - $args['items'] + 1;
		$end = $max_page_num;
	} else {
		$start = $befores;
		$end = $afters;
	}

	$elm_attrs = '';
	if ( $args['elm_id'] ) {
		$elm_attrs = ' id="' . $args['elm_id'] . '"';
	}
	if ( $args['elm_class'] ) {
		$elm_attrs .= ' class="' . $args['elm_class'] . '"';
	}

	$num_list_item = '';
	if ( $args['show_num'] ) {
		$num_list_item = '<li class="page_nums';
		if ( $args['li_class'] ) {
			$num_list_item .= ' ' . $args['li_class'];
		}
		$num_list_item .= '">' . sprintf( $args['num_format'], $current_page_num, $max_page_num ) . "</li>\n";
	}

	$page_navi = '';
	if ( $elm ) {
		$elm_tabs = "\t";
		$page_navi = $tabs . '<' . $elm;
		if ( $elm_attrs ) {
			$page_navi .= $elm_attrs . ">\n";
		}
	}

	$page_navi .= $elm_tabs . $tabs . '<ul';
	if ( ! $elm && $elm_attrs ) {
		$page_navi .= $elm_attrs;
	}
	$page_navi .= ">\n";

	if ($args['num_position'] != 'after' && $num_list_item ) {
		$page_navi .= "\t" . $elm_tabs . $tabs . $num_list_item;
	}
	if ( $args['show_boundary'] && ( $current_page_num != 1 || in_array( $args['edge_type'], array( 'span', 'link' ) ) ) ) {
		$page_navi .= "\t" . $elm_tabs . $tabs . '<li class="' . $args['class_prefix'] . 'first';
		if ( $args['li_class'] ) {
			$page_navi .= ' ' . $args['li_class'];
		}
		if ( $args['edge_type'] == 'span' && $current_page_num == 1 ) {
			$page_navi .= '"><span>' . esc_html( $args['first_label'] ) . '</span></li>' . "\n";
		} else {
			$page_navi .= '"><a href="' . get_pagenum_link() . '">' . esc_html( $args['first_label'] ) . '</a></li>' . "\n";
		}
	}

	if ( $args['show_adjacent'] && ( $current_page_num != 1 || in_array( $args['edge_type'], array( 'span', 'link' ) ) ) ) {
		$previous_num = max( 1, $current_page_num - 1 );
		$page_navi .= "\t" . $elm_tabs . $tabs . '<li class="' . $args['class_prefix'] . 'previous';
		if ( $args['li_class'] ) {
			$page_navi .= ' ' . $args['li_class'];
		}
		if ( $args['edge_type'] == 'span' && $current_page_num == 1 ) {
			$page_navi .= '"><span>' . esc_html( $args['prev_label'] ) . '</span></li>' . "\n";
		} else {
			$page_navi .= '"><a href="' . get_pagenum_link( $previous_num ) . '">' . esc_html( $args['prev_label'] ) . '</a></li>' . "\n";
		}
	}

	for ( $i = $start; $i <= $end; $i++ ) {
		$page_navi .= "\t" . $elm_tabs . $tabs . '<li class="';
		if ( $i == $current_page_num ) {
			$page_navi .= $args['current_class'];
			if ( $args['li_class'] ) {
				$page_navi .= ' ' . $args['li_class'];
			}
			$page_navi .= '">' . sprintf( $args['current_format'], $i ) . "</li>\n";
		} else {
			$delta = absint( $i - $current_page_num );
			$b_f = $i < $current_page_num ? 'before' : 'after';
			$page_navi .= $args['class_prefix'] . $b_f . ' ' . $args['class_prefix'] . 'delta-' . $delta;
			if ( $i == $start ) {
				$page_navi .= ' ' . $args['class_prefix'] . 'head';
			} elseif ( $i == $end ) {
				$page_navi .= ' ' . $args['class_prefix'] . 'tail';
			}
			if ( $args['li_class'] ) {
				$page_navi .= ' ' . $args['li_class'] . '"';
			}
			$page_navi .= '"><a href="' . get_pagenum_link( $i ) . '">' . $i . "</a></li>\n";
		}
	}

	if ( $args['show_adjacent'] && ( $current_page_num != $max_page_num || in_array( $args['edge_type'], array( 'span', 'link' ) ) ) ) {
		$next_num = min( $max_page_num, $current_page_num + 1 );
		$page_navi .= "\t" . $elm_tabs . $tabs . '<li class="' . $args['class_prefix'] . 'next';
		if ( $args['li_class'] ) {
			$page_navi .= ' ' . $args['li_class'];
		}
		if ( $args['edge_type'] == 'span' && $current_page_num == $max_page_num ) {
			$page_navi .= '"><span>' . esc_html( $args['next_label'] ) . '</span></li>' . "\n";
		} else {
			$page_navi .= '"><a href="' . get_pagenum_link( $next_num ) . '">' . esc_html( $args['next_label'] ) . '</a></li>' . "\n";

		}
	}

	if ( $args['show_boundary'] && ( $current_page_num != $max_page_num || in_array( $args['edge_type'], array( 'span', 'link' ) ) ) ) {
		$page_navi .= "\t" . $elm_tabs . $tabs . '<li class="' . $args['class_prefix'] . 'last';
		if ( $args['li_class'] ) {
			$page_navi .= ' ' . $args['li_class'];
		}
		if ( $args['edge_type'] == 'span' && $current_page_num == $max_page_num ) {
			$page_navi .= '"><span>' . esc_html( $args['last_label'] ) . '</span></li>' . "\n";
		} else {
			$page_navi .= '"><a href="' . get_pagenum_link( $max_page_num ) . '">' . esc_html( $args['last_label'] ) . '</a></li>' . "\n";
		}
	}

	if ($args['num_position'] == 'after' && $num_list_item ) {
		$page_navi .= "\t" . $elm_tabs . $tabs . $num_list_item;
	}

	$page_navi .= $elm_tabs . $tabs . "</ul>\n";

	if ( $elm ) {
		$page_navi .= $tabs . '</' . $elm . ">\n";
	}

	$page_navi = apply_filters( 'page_navi', $page_navi );

	if ( $args['echo'] ) {
		echo $page_navi;
	} else {
		return $page_navi;
	}
}


private function sanitize_attr_classes( $classes, $prefix = '' ) {
	if ( ! is_array( $classes ) ) {
		$classes = preg_replace( '/[^\s\w_-]+/', '', $classes );
		$classes = preg_split( '/[\s]+/', $classes );
	}

	foreach ( $classes as $key => $class ) {
		if ( is_array( $class ) ) {
			unset( $classes[$key] );
		} else {
			$class = preg_replace( '/[^\w_-]+/', '', $class );
			$class = preg_replace( '/^[\d_-]+/', '', $class );
			if ( $class ) {
				$classes[$key] = $prefix . $class;
			}
		}
	}
	$classes = implode( ' ', $classes );

	return $classes;
}


private function uniform_boolean( $arg, $default = true ) {
	if ( is_numeric( $arg ) ) {
		$arg = (int)$arg;
	}
	if ( is_string( $arg ) ) {
		$arg = strtolower( $arg );
		if ( $arg == 'false' ) {
			$arg = false;
		} elseif ( $arg == 'true' ) {
			$arg = true;
		} else {
			$arg = $default;
		}
	}
	return $arg;
}


} // class end
$prime_strategy_page_navi = new prime_strategy_page_navi();

if ( ! function_exists( 'page_navi' ) ) {
	function page_navi( $args = '' ) {
		global $prime_strategy_page_navi;
		return $prime_strategy_page_navi->page_navi( $args );
	}
}