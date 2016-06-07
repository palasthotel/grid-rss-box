
<ul class="grid-rss-items">
  <?php
  foreach($content as $item){
	/**
	 * @var grid_rss_box_item $item
	 */
	?>
	<li class="<?php echo implode(" ", $item->getClasses()); ?>"><a href="<?php
	  	echo $item->getPermalink();
	  ?>"><?php
	  	echo $item->getDate("d.M.Y - H:i")."<br>";
		echo $item->getTitle()."<br>";
		echo $item->getDescription()
	  ?></a></li>
  <?php
  }
  ?>
</ul>