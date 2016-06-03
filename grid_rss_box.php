<?php

class grid_rss_box extends grid_list_box
{

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
	if($editmode)
	{
	  return t("RSS Feed");
	}
	else
	{
	  if(isset($this->content->url) && $this->content->url!="")
	  {
		$pie=new SimplePie();
		$pie->set_cache_location('sites/default/files/rss_cache');
		if(!file_exists("sites/default/files/rss_cache"))
		{
		  mkdir("sites/default/files/rss_cache");
		}
		$pie->set_feed_url($this->content->url);
		$pie->set_item_limit($this->content->numItems);
		$pie->init();
		$this->feed=$pie;
		$output="<div class='item-list'><ul>";
		$i=0;
		foreach($pie->get_items(0,$this->content->numItems) as $item)
		{

		  $output.="<li";
		  if($i==0)
			$output.=" class='first'";
		  if($i==$this->content->numItems-1)
			$output.=" class='last'";
		  $output.=">";
		  $output.="<a class='aggregator clearfix' href='".$item->get_permalink()."'><span role='title'>".$item->get_title()."</span>";
		  if(isset($this->content->show_date) && $this->content->show_date)
		  {
			$output.="<br>";
			$output.="<span role='date'>".$item->get_date("d.M.Y - H:i")."</span>";
		  }
		  if(isset($this->content->show_image) && $this->content->show_image)
		  {
			$imagesearch=$item->get_description();
			$matching=array();
			if(preg_match('/<img.*src="([^"]*)"[^>]*>/', $imagesearch,$matching))
			{
			  $img="<div class='image_wrapper'><img src='".$matching[1]."'></div>";
			}
			else
			  $img="";
			$output.=$img;
		  }
		  if($this->content->display=='teaser')
		  {
			$text=$item->get_description();
			$text=strip_tags($text);
			if(strlen($text)>$this->content->teaser_length)
			{
			  if($text[$this->content->teaser_length]!=' ' && $text[$this->content->teaser_length]!=' ')
			  {
				$text=substr($text,0,$this->content->teaser_length);
				$idx=strrpos($text, " ");
				$text=substr($text,0,$idx);
			  }
			  else
			  {
				$text=substr($text,0,$this->content->teaser_length);
			  }
			  $text.="…";
			}
			$output.="<div class='teasertext_wrapper'><span class='teasertext'>".$text."</span></div>";
		  }
		  $output.="</a>";
		  $output.="</li>";
		  $i++;
		}
		$output.="</ul></div>";
		return $output;
	  }
	  return "";
	}
  }

  public function contentStructure() {
	return array(
	  array(
		'key'=>'url',
		'label'=>t('RSS-URL'),
		'type'=>'text',
	  ),
	  array(
		'key'=>'display',
		'type'=>'select',
		'label'=>t('Display Mode'),
		'selections'=>array(
		  array('key'=>'title','text'=>t('Title only')),
		  array('key'=>'teaser','text'=>t('Title and teaser')),
		),
	  ),
	  array(
		'key'=>'teaser_length',
		'label'=>t('Teaser text length'),
		'type'=>'number',
	  ),
	  array(
		'key'=>'show_date',
		'label'=>t('Show dates'),
		'type'=>'checkbox',
	  ),
	  array(
		'key'=>'show_image',
		'label'=>t('Show first image from text'),
		'type'=>'checkbox',
	  ),
	  array(
		'key'=>'numItems',
		'label'=>t('Number of items to show'),
		'type'=>'number',
	  ),
	);
  }

}