=== Prime Strategy Page Navi ===
Contributors: jim912
Tags: page navigation, page navi, pagenation
Requires at least: 2.5
Tested up to: 3.4
Stable tag: 1.0.2

Adds the functions to display page navigation (pagination) on home and archives (categories, authors, date and so on).

== Description ==
This plugin adds the functions to display page navigation (pagination) on home and archives (categories, authors, date and so on). You can use lots of parameters, and you can customize pagination flexibly.

= Examples =
**Defualt Setting**<br />
Template Tag<br />
`<?php if ( function_exists( 'page_navi' ) ) page_navi(); ?>`
Output sample<br />
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

**Customize sample**<br />
Template Tag<br />
`<?php if ( function_exists( 'page_navi' ) ) page_navi( 'items=7&amp;prev_label=Prev&amp;next_label=Next&amp;first_label=First&amp;last_label=Last&amp;show_num=1&amp;num_position=after' ); ?>`
Output sample<br />
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

1. Upload Prime Strategy Page Navi plugin folder you downloaded to the plugin directory.
2. Go to the plugin menu of Admin, and activate "Prime Strategy Page Navi" plugin.
3. Add a template tag "page_navi" to the place where you would like to display page navigation in your theme. See below about parametes you can specify by template tags.

= Parameters =

**items**<br />
Number of next/prev nagitaions displayed. This number includes current page, so specify odd if you display same numbers on next and prev navi. 
Default: 11

**edge_type**<br />
How to display next/previous link on first and last pages and link to the first and last pages.
none: links are not displayed, span: page numbers  are displayed without link, link: links are displayed
Default: none

**show_adjacent**<br />
Specify if links to adjacent pages are displayed.
Default: true (displayed)

**prev_label**<br />
Text of the link to previous page. Default: &amp;lt; (<)

**next_label**<br />
Text of the link to next page. Default:  &amp;gt; (>)

**show_boundary**<br />
Specify if links to the first and last pages are displayed.
Default: true (displayed)

**first_label**<br />
Text of the link to the first page.
Default: &amp;laquo; («)

**last_label**<br />
Text of the link to the last page.
Default: &amp;raquo; (»)

**show_num**<br />
Specify if the numbers of current page and all pages are displayed.
Default: false (not displayed)

**num_position**<br />
Specify where the numbers of current page  and all pages are displayed.
Default: before. If you would like to display after navigations, specify "after".

**num_format**<br />
Format to display cuthe numbers of current page and all pages.
Default: <span>%d/%d</span> (nn/mm)

**navi_element**<br />
Wrapper element of page navi. You can specify div or nav.
Default: none (no wrapper element)

**elm_class**<br />
Class of wrapper element, or ul class if no wrapper element.
Default: page_navi

**elm_id**<br />
Id of wrapper element, or ul id if no wrapper element.
Default: none (no id)

**li_class**<br />
Class of all lis added to page navi.
Default: none (no class)

**current_class**<br />
Class specified on li in current page.
Default: current

**current_format**<br />
Format to display current page text.
Default: <span>%d</span>

**class_prefix**<br />
Class prefix. This is added to all classes that page navi outputs.
Default: none (no prefix)

**indent**<br />
Number of tab indent.
Default: 0

**echo**<br />
Output or not. Default: true (output).
If you specify 0 or false, return values as PHP.


== Changelog ==
= 1.0.2 =
* Compatible with WordPress 3.4

= 1.0.1 =
* Compatible with WordPress 3.3

= 1.0.0 =
* Opening on WordPress Plugin Directory.

= 0.8.1 =
* Fixed the issue that true-values are not handled properly.
* Added edge_type parameter.

= 0.8.0 =
* Opening to the public.


== Screenshots ==
1. Output Sample of a page navi(Default)
2. Output Sample of a page navi(Customized)

== Links ==
"[PS Taxonomy Expander](http://wordpress.org/extend/plugins/ps-taxonomy-expander/ "WordPress sitemap plugin")" makes categories, tags and custom taxonomies more useful.

"[PS Auto Sitemap](http://wordpress.org/extend/plugins/ps-auto-sitemap/ "WordPress sitemap plugin")" is a plugin automatically generates a site map page from your WordPress site. 
It is easy to install for beginners and easy to customize for experts.
It can change the settings of the display of the lists from administration page, several neat CSS skins for the site map tree are prepared.

"[PS Disable Auto Formatting](http://wordpress.org/extend/plugins/ps-disable-auto-formatting/ "WordPress formatting plugin")"
Stops the automatic forming and the HTML tag removal in the html mode of WordPress, and generates a natural paragraph and changing line.

"[CMS service with WordPress](http://www.prime-strategy.co.jp/ "CMS service with WordPress")" provides you service that uses WordPress as a CMS.
