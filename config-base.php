<?php


$wprd = new WPRD( 'WPRD' );


/**=====================================================
 *	基本設定
 *=====================================================*/
// Editor CSS へのパス
add_editor_style( '/assets/css/editor-style.css' );

// 管理画面用 CSS の追加
$wprd->settings->add_admin_style('/includes/admin/css/admin-style.css');

// 管理画面用 JS の追加
$wprd->settings->add_admin_script('/includes/admin/js/admin_edit.js', ['edit.php']);

// 言語フォルダを読み込むフォルダ名を指定
$wprd->settings->set_load_theme_textdomain('languages');

// html5 のマークアップ
// title-tag
// feed-links
// 各サポートの有効化
$wprd->settings->on_support();

// 日本語スラッグの禁止　※日本語を入れた場合自動で entry-{ID} となります
$wprd->settings->on_post_auto_slug();

// リダイレクト時の推測候補先への遷移を禁止
$wprd->settings->remove_auto_redirect();

// 管理画面の Wordpress ロゴを消去
$wprd->settings->remove_wp_admin_logo();

// 管理画面の投稿関係のメニューをメニュー上部にまとめる
$wprd->settings->reorder_admin_menu();

// wp_head() で出力される各種項目の除去
$wprd->settings->clean_wp_head([
	'wp_generator',
	'wp_shortlink_wp_head',
	//'feed_links',
	//'feed_links_extra',

	/* EditURI */
	'rsd_link',

	/* wlwmanifest */
	'wlwmanifest_link',

	/* page-links */
	'index_rel_link',
	'parent_post_rel_link',
	'start_post_rel_link',
	'adjacent_posts_rel_link_wp_head',

	/* oEmbed */
	//'rest_output_link_wp_head',
	'wp_oembed_add_discovery_links',
	'wp_oembed_add_host_js',

	/* emoji */
	'print_emoji_detection_script',
	'print_emoji_styles',

	/* inline-style */
	'recent_comments_style',
]);


/**=====================================================
 *	メール設定
 *=====================================================*/
add_filter('wp_mail_from', function($email) {
	// 送信側メールアドレス
	return 'wordpress@example.com';
});
add_filter('wp_mail_from_name', function($email_name) {
	// 送信者名
	return $email_name;
});

/**=====================================================
 *	エディタ関係
 *=====================================================*/
// ビジュアルエディタの各種ボタンの有効化
$wprd->editor->on_buttons_visual_editor();

// テキストエディタの各種ボタンの有効化
$wprd->editor->on_buttons_text_editor();

/**=====================================================
 *	カテゴリー・タグ（タクソノミー）関連
 *=====================================================*/
// カテゴリーの選択項目に「設定しない」を追加
// カテゴリー選択をチェックボックスからラジオボタンに変更
$wprd->settings->switch_to_radio_type_category();

/**=====================================================
 *	カスタムポスト関連
 *=====================================================*/
// 追加するポストタイプ（wprd_CustomPost.php にて記述）
$wprd->cpost->add(['news', 'wordpress']);

/**=====================================================
 *	カスタムフィールド関連
 *=====================================================*/
// カスタムフィールドを適応する投稿タイプを指定
$wprd->cfield->init(['post', 'wordpress']);

/**=====================================================
 *	カスタムメニュー（ナビゲーション）
 *=====================================================*/
// カスタムメニューの場所の名称を指定
$wprd->nav->init([
	'primary'		=> __('メインメニュー'),
	'social'		=> __('ソーシャル'),
]);

$wprd->options->init();
