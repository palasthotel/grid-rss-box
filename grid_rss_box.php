<?php

if(!class_exists("SimplePie")) require dirname(__FILE__)."/simplepie_1.3.1.mini.php";

class grid_rss_box extends grid_list_box
{
  public static $CACHE_DIR = "cache/";

  public function __construct()
  {
	parent::__construct();
	$this->content->url="";
	$this->content->numItems=15;
  }

  public function type()
  {
	return 'rss';
  }

  public function build($editmode)
  {
	if(isset($this->content->url) && $this->content->url!="")
	{
	  $pie=new SimplePie();
	  $pie->set_cache_location(self::$CACHE_DIR);
	  if(!file_exists(self::$CACHE_DIR))
	  {
		mkdir(self::$CACHE_DIR);
	  }

	  $pie->set_feed_url($this->content->url);
	  $pie->set_item_limit($this->content->numItems);

	  $pie->init();
	  $this->feed=$pie;

	  $i=$this->content->numItems;
	  $items = array();

	  foreach($pie->get_items(0,$this->content->numItems) as $item)
	  {

		$_item = new grid_rss_box_item($item);

		if($i==0) $_item->addClass("grid-rss-item-first");
		if($i==$this->content->numItems-1) $_item->addClass("grid-rss-item-last");

		$items[] = $_item;

		/*
		 * stop it on max items
		 */
		$i--;
		if($i < 0) break;

	  }

	  return $items;
	}
	return t("RSS Feed");

  }

  public function contentStructure() {
	return array(
	  array(
		'key'=>'url',
		'label'=>t('RSS-URL'),
		'type'=>'text',
	  ),
	  array(
		'key'=>'numItems',
		'label'=>t('Number of items to show'),
		'type'=>'number',
	  ),
	);
  }

}

class grid_rss_box_item{

  private $raw;
  private $classes;

  function __construct($raw) {
	$this->raw = $raw;
	$this->classes = array(
	  "grid-rss-item",
	);
  }

  public function addClass($class){
	$this->classes[] = $class;
  }

  public function getClasses(){
	return $this->classes;
  }

  public function getTitle(){
	return $this->raw->get_title();
  }

  public function getDescription(){
	return $this->raw->get_description();
  }

  public function getDate($format) {
	return $this->raw->get_date($format);
  }
  public function getPermalink(){
	return $this->raw->get_permalink();
  }


}