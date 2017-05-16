<?php
namespace WPRD\module\controller;
/**
 * WPRD Controller
 * @since 0.1
 */
class Controller
{
	protected $view_vars	= [];
	protected $params		= [];
	protected $post_type	= '';

	/**
	 * set
	 * @since 0.1
	 */
	public function set( $post_type, $render_type = false )
	{
		global $wp_query;

		$this->post_type = ( (string) $post_type !== '' && $post_type !== 'default' ) ? $post_type : '';

		if( !is_search() ) {
			$this->params['post_type'] = $this->post_type;
		}

		// 投稿件数の取得
		$this->view_vars['found_posts'] = $wp_query->found_posts;
		// 投稿件数が0の時
		$render_type = ( $this->view_vars['found_posts'] ) ? $render_type : 'none';

		// モデルクラスのロード
		$this->model = $this->load_model($this->post_type);

		$this->view_vars['post'] = $this->model->get($this->params);

		$this->render($render_type);
	}

	/**
	 * render
	 * @since 0.1
	 */
	protected function render( $render_type = false )
	{
		$render_type = (string)$render_type;

		if( $file = $this->request_views( $render_type ) ) {
			extract($this->view_vars);
			require( $file );
		}
		else {
			// 読むこむべきファイルが見つからない場合
		}

	}


	/**
	 * load_model
	 * @since 0.1
	 */
	protected function load_model( $post_type = false )
	{
		return request_module( 'model', $post_type);
	}


	/**
	 * @return $post_type_name
	 */
	protected function get_post_type()
	{
		if( is_archive() ) {

			if( is_tax() || is_tag() || is_category() ) {
				/* taxonomy archive */
				if( !isset($this->params['tax_query']) || !is_array($this->params['tax_query']) ) {
					$this->params['tax_query'] = [];
				}
				$this->params['tax_query'] = [
					[
						'taxonomy'	=> get_queried_object()->taxonomy,
						'terms'			=> get_queried_object()->slug,
						'field'			=> 'slug',
						'operator'	=> 'and',
					]
				];
				return get_taxonomy($this->params['tax_query'][0]['taxonomy'])->object_type[0];
			}
			/* get_query_varで投稿数0の時にもpost_typeを返す */
			return get_query_var('post_type');
		}
		return get_post_type();
	}


	/**
	 * request_views
 	 * @param $render_type, string
	 *=====================================================*/
	private function request_views( $render_type )
	{
		$module_path = WPRD_MDLS_PATH . '/view/';

		$filename = !empty($render_type) ?
			$this->post_type . '-' . $render_type
		:	$this->post_type;

		// 指定ファイル名　→　デフォルト-レンダータイプ　→　デフォルト　と順に検索
		if( is_file( $file = $module_path . $filename . '.php' )
					||
			( is_file( $file = $module_path . $this->post_type . '.php' ) )
					||
			( is_file( $file = $module_path . 'default-' . $render_type . '.php' ) )
					||
			( is_file( $file = $module_path . 'default.php' ) )
		) {
			return $file;
		}
		return false;
	}

}
