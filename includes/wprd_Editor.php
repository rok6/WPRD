<?php

class WPRD_Editor
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;
	}


	/**
	 * ビジュアルエディタのボタン・機能追加
	 *
	 * @since 0.1
	 */
	public function on_buttons_visual_editor()
	{
		add_filter('mce_external_plugins', function( $plugins ) {
			// テーブルプラグイン
			$plugins['table'] = '//cdn.tinymce.com/4/plugins/table/plugin.min.js';
			// ビジュアルエディタ上で現在のソースコードを確認
			$plugins['code'] = '//cdn.tinymce.com/4/plugins/code/plugin.min.js';
			// 各スクリプトコードサンプル記載用
			$plugins['codesample'] = '//cdn.tinymce.com/4/plugins/codesample/plugin.min.js';
			// 独自ボタン
			$plugins['custom_my_mce'] = get_template_directory_uri() . '/includes/admin/js/admin_post_mce.js';
			return $plugins;
		});

		add_filter('tiny_mce_before_init', function( $mceInit ) {
			// Custom EDIT buttons
			$mceInit['toolbar1'] = 'spellchecker,formatselect,bold,italic,underline,strikethrough,superscript,subscript,serif,link,unlink,table,wp_adv,fullscreen';
			$mceInit['toolbar2'] = 'undo,redo,removeformat,bullist,numlist,dlist-dl,dlist-dt,dlist-dd,blockquote,cite,attention,del,ins,alignleft,aligncenter,alignright';
			$mceInit['toolbar3'] = 'indent,outdent,hr,pre,wp_code,codesample,wp_more,wp_page,charmap,pastetext,code,wp_help';
			$mceInit['block_formats'] = "段落=p; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5;";
			// $mceInit['indent'] = true;
			// iframe の追加
			//$mceInit[ 'extended_valid_elements' ] .= "iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]";
			return $mceInit;
		});
	}

	/**
	 * テキストエディタのボタン・機能追加
	 *
	 * @since 0.1
	 */
	public function on_buttons_text_editor()
	{
		add_action('admin_enqueue_scripts', function( $hook ) {
			if( ('post.php' === $hook || 'post-new.php' === $hook) ) {
				wp_enqueue_script( 'my_custom_script', get_template_directory_uri().'/includes/admin/js/admin_post_quicktext.js', false, null, true );
			}
		});

		add_filter( 'quicktags_settings', function( $tags ) {
			//"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"
			$tags['buttons'] = 'close,link,more,dfw';
			return $tags;
		});
	}

}
