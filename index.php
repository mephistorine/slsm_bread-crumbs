<?php
/*
	Plugin Name: Хлебные крошки
	Description: Добавляет полный путь в title страницы
	Author: stylesam
	Author URI: http://stylesam.com/
	Version: 1.0
	License: MIT
*/

class SLSMBreadCrumbs
{

	/**
	 * SLSMBreadCums constructor.
	 */
	public function __construct()
	{
		add_filter('wp_title', [$this, 'slsm_title'], 20);
	}

	public function slsm_title( $title )
	{
		$sep = ' / ';
		$site_name = get_bloginfo('name');

		if ( is_home() || is_front_page() )
		{
			$title = [$site_name];
		}

		else if ( is_page() )
		{
			$title = [get_the_title(), $site_name];
		}
		else if ( is_tag() )
		{
			$title = [single_tag_title('Метка: ', false), $site_name];
		}
		else if ( is_search() )
		{
			$title = ['Результаты по запросу: ' . get_search_query()];
		}
		else if ( is_404() )
		{
			$title = ['Страница не найдена'];
		}
		else if ( is_category() )
		{
			$cat_id = get_query_var( 'cat' );
			$cat_data = get_category( $cat_id );

			if ( $cat_data->parent )
			{
				$categories = rtrim(get_category_parents( $cat_id, false, $sep ), $sep);
				$categories = explode($sep, $categories);
				$title = array_reverse($categories);
				$title[] = $site_name;
			}
			else
			{
				$title = [$cat_data->name, $site_name];
			}
		}
		else if ( is_single() )
		{
			$cat = get_the_category();
			$cat_id = $cat[0]->cat_ID;
			$categories = rtrim(get_category_parents( $cat_id, false, $sep ), $sep);
			$categories = explode($sep, $categories);
			$categories[] = get_the_title();
			$title = array_reverse($categories);
			$title[] = $site_name;
		}
		else if ( is_archive() )
		{
			$title = ['Архив за: ' . get_the_time('F Y'), $site];
		}

		$title = implode($sep, $title);
		return $title;
	}
}

$slsm_bread_crumbs = new SLSMBreadCrumbs();