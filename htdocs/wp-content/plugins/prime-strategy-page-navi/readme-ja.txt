=== Prime Strategy Page Navi ===
Contributors: jim912
Tags: page navigation, page navi, pagenation
Requires at least: 2.5
Tested up to: 3.4
Stable tag: 1.0.2

ホームやアーカイブページ（カテゴリー、作成者、年月など）へのページナビ（ページネーション）表示機能を追加します。

== Description ==
ホームやアーカイブページ（カテゴリー、作成者、年月など）へのページナビ（ページネーション）表示機能を追加します。表示内容のカスタマイズは、多数のパラメータが用意されており、フレキシブルなカスタマイズが可能です。

= Examples =
**デフォルト設定**<br />
テンプレートタグ<br />
`<?php if ( function_exists( 'page_navi' ) ) page_navi(); ?>`
出力サンプル<br />
`
<ul class="page_navi">
	<li class="first"><a href="http://www.example.com/">&amp;laquo;</a></li>
	<li class="previous"><a href="http://www.example.com/page/5/">&amp;lt;</a></li>
	<li class="before delta-5 head"><a href="http://www.example.com/">1</a></li>
	<li class="before delta-4"><a href="http://www.example.com/page/2/">2</a></li>
	<li class="before delta-3"><a href="http://www.example.com/page/3/">3</a></li>
	<li class="before delta-2"><a href="http://www.example.com/page/4/">4</a></li>
	<li class="before delta-1"><a href="http://www.example.com/page/5/">5</a></li>
	<li class="current"><span>6</span></li>
	<li class="after delta-1"><a href="http://www.example.com/page/7/">7</a></li>
	<li class="after delta-2"><a href="http://www.example.com/page/8/">8</a></li>
	<li class="after delta-3"><a href="http://www.example.com/page/9/">9</a></li>
	<li class="after delta-4"><a href="http://www.example.com/page/10/">10</a></li>
	<li class="after delta-5 tail"><a href="http://www.example.com/page/11/">11</a></li>
	<li class="next"><a href="http://www.example.com/page/7/">&amp;gt;</a></li>
	<li class="last"><a href="http://www.example.com/page/17/">&amp;raquo;</a></li>
</ul>
`

**カスタマイズ例**<br />
テンプレートタグ<br />
`<?php if ( function_exists( 'page_navi' ) ) page_navi( 'items=7&amp;prev_label=Prev&amp;next_label=Next&amp;first_label=First&amp;last_label=Last&amp;show_num=1&amp;num_position=after' ); ?>`
出力サンプル<br />
`
<ul class="page_navi">
	<li class="first"><a href="http://www.example.com/">First</a></li>
	<li class="previous"><a href="http://www.example.com/page/5/">Prev</a></li>
	<li class="before delta-3 head"><a href="http://www.example.com/page/3/">3</a></li>
	<li class="before delta-2"><a href="http://www.example.com/page/4/">4</a></li>
	<li class="before delta-1"><a href="http://www.example.com/page/5/">5</a></li>
	<li class="current"><span>6</span></li>
	<li class="after delta-1"><a href="http://www.example.com/page/7/">7</a></li>
	<li class="after delta-2"><a href="http://www.example.com/page/8/">8</a></li>
	<li class="after delta-3 tail"><a href="http://www.example.com/page/9/">9</a></li>
	<li class="next"><a href="http://www.example.com/page/7/">Next</a></li>
	<li class="last"><a href="http://www.example.com/page/17/">Last</a></li>
	<li class="page_nums"><span>6/17</span></li>
</ul>
`
**CSS Sample**
`
.page_navi {
    text-align: center;
}
 
.page_navi li {
    display: inline;
    list-style: none;
}
 
.page_navi li.current span {
    color: #000;
    font-weight: bold;
    display: inline-block;
    padding: 3px 7px;
    background: #fee;
    border: solid 1px #fcc;
}
 
.page_navi li a {
    color: #333;
    padding: 3px 7px;
    background: #eee;
    display: inline-block;
    border: solid 1px #999;
    text-decoration: none;
}
 
.page_navi li a:hover {
    color: #f00;
}
 
.page_navi li.page_nums span {
    color: #fff;
    padding: 3px 7px;
    background: #666;
    display: inline-block;
    border: solid 1px #333;
}
`

= Special Thanks =
English Translation:[Odyssey](http://www.odysseygate.com/ "Translation")

== Installation ==

1. pluginsフォルダに、ダウンロードした Prime Strategy Page Navi のフォルダをアップロードしてください。
2. プラグインページで "Prime Strategy Page Navi" を有効化して下さい。
3. 利用しているテーマのページナビを表示したい箇所にページナビのテンプレートタグ "page_navi" を追加してください。テンプレートタグで指定できるパラメータについては、下記の Parameters を参照してください。

= Parameters =

**items**<br />
表示する前後ナビゲーションの数。現状表示しているページを含むため、前後の表示数を揃えたい場合は奇数を指定してください。デフォルトは１１

**edge_type**<br />
１ページ目および最終ページでの前後ページへのリンク、最初と最後のページへのリンクの表示方法。noneで表示しない。spanでリンクなしで表示。linkはリンク付きで表示。デフォルトはnone

**show_adjacent**<br />
前後ページへのリンクを表示するかどうか。デフォルトはtrue（表示）

**prev_label**<br />
前ページリンクのリンクテキスト。デフォルトは、&amp;lt;（<）

**next_label**<br />
次ページリンクのリンクテキスト。デフォルトは、&amp;gt;（>）

**show_boundary**<br />
最初と最後のページへのリンクを表示するかどうか。デフォルトはtrue（表示）

**first_label**<br />
最初のページへのリンクテキスト。デフォルトは&amp;laquo;（«）

**last_label**<br />
最後のページへのリンクテキスト。デフォルトは&amp;raquo;（»）

**show_num**<br />
現ページナンバーと全ページ数の表示をするかどうか。デフォルトはfalse（非表示）

**num_position**<br />
現ページナンバーと全ページ数の表示位置。デフォルトはbefore（前）。後に表示したい場合はafterを指定

**num_format**<br />
現ページナンバーと全ページ数の表示フォーマット。デフォルトは、<span>%d/%d</span>（nn/mm）

**navi_element**<br />
ページナビのラッパー要素。divかnavを指定可能。デフォルトは空（ラッパー要素なし）

**elm_class**<br />
ラッパー要素、ラッパー要素がない場合は ulのclass属性。デフォルトはpage_navi

**elm_id**<br />
ラッパー要素、ラッパー要素がない場合は ulのid属性。デフォルトは空（id要素なし）

**li_class**<br />
ページナビの全liに付くclass属性。デフォルトは空（classなし）

**current_class**<br />
現ページのliに指定されるクラス名。デフォルトは current

**current_format**<br />
現ページの表示フォーマット。デフォルトは <span>%d</span>

**class_prefix**<br />
classの接頭辞。ページナビで出力されるclass全てに追加される。デフォルトは空。（接頭辞なし）

**indent**<br />
タブインデント数。デフォルトは０

**echo**<br />
ページナビの出力を行うかどうか。デフォルトは true（出力する）。false または 0 を指定するとPHPの値として returnする 


== Changelog ==
= 1.0.2 =
* WordPress 3.4対応

= 1.0.1 =
* WordPress 3.3対応

= 1.0.0 =
* 公式ディレクトリ公開

= 0.8.1 =
* 真偽値のパラメータが正しく反映されない問題を修正
* edge_typeパラメータを追加

= 0.8.0 =
* 一般公開


== Screenshots ==
1. ページナビ出力サンプル（デフォルト）
2. ページナビ出力サンプル（カスタマイズ例）

== Links ==
"[PS Taxonomy Expander](http://wordpress.org/extend/plugins/ps-taxonomy-expander/ "WordPress sitemap plugin")" makes categories, tags and custom taxonomies more useful.

"[PS Auto Sitemap](http://wordpress.org/extend/plugins/ps-auto-sitemap/ "WordPress sitemap plugin")" is a plugin automatically generates a site map page from your WordPress site. 
It is easy to install for beginners and easy to customize for experts.
It can change the settings of the display of the lists from administration page, several neat CSS skins for the site map tree are prepared.

"[PS Disable Auto Formatting](http://wordpress.org/extend/plugins/ps-disable-auto-formatting/ "WordPress formatting plugin")"
Stops the automatic forming and the HTML tag removal in the html mode of WordPress, and generates a natural paragraph and changing line.

"[CMS service with WordPress](http://www.prime-strategy.co.jp/ "CMS service with WordPress")" provides you service that uses WordPress as a CMS.
