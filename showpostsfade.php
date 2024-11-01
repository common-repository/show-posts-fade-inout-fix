<?php
/*
Plugin Name: Show Category Posts Fade in/out
Plugin URI: http://wordpress.geegood.com/plugins/show-category-posts-fade-inout/
Description: Displays featured posts by randomly selecting posts from a designated category and fading them in and out using jQuery.
Author: http://wordpress.geegood.com
Version: 0.2.3
Author URI: http://wordpress.geegood.com/
*/ 

/*  Copyright 2010  info @ geegood.com  (email : info@geegood.com)

	Added code jQuery, CSS, fade in/out code by http://wordpress.geegood.com - Niels Ulrik Reinwald
	 
	Original code by: http://www.mydollarplan.com/random-featured-post-plugin/
    
	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function addHeaderCode() {
	
echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') .'/wp-content/plugins/show-posts-fade-inout-fix/style.css" />' . "\n";
			
			
			wp_enqueue_script('jquery');

/*

			if (function_exists('wp_enqueue_script')) {
                
				
				wp_register_script('geegood_com_show_posts_fade', get_bloginfo('wpurl') . '/wp-content/plugins/show-posts-fade/script.js', false ,'0.1');
			
			wp_enqueue_script( 'geegood_com_show_posts_fade' );
				
				
				
            }	*/
		}
		
		
		
add_action('wp_head', 'addHeaderCode', 1);


function show_post_fade_option_menu() {
	if (function_exists('current_user_can')) {
		if (!current_user_can('manage_options')) return;
	} else {
		global $user_level;
		get_currentuserinfo();
		if ($user_level < 8) return;
	}
	if (function_exists('add_options_page')) {
		add_options_page(__('Show Posts Fade'), __('Show Posts Fade'), 1, __FILE__, 'show_post_fade_options_page');
	}
}

add_action('admin_menu', 'show_post_fade_option_menu');

$default_options['show_featured'] = '';
$default_options['show_category'] = '';
$default_options['div_title'] = 'Featured Post';
$default_options['more_text'] = '...read more...'; // &rarr;';
$default_options['show_excerpt'] = 'Y';
$default_options['excerpt_letters'] = '50';

$default_options['show_headline_only'] = 'N';
$default_options['headline_size'] = '1.5';


$default_options['ignore_more'] = '';
$default_options['num_posts'] = '3';
$default_options['show_posts'] = '';
$default_options['show_pages'] = '';
$default_options['image_fade_out'] = '800';
$default_options['image_fade_in'] = '1200';
$default_options['txt_fade_out'] = '400';
$default_options['txt_fade_in'] = '200';


add_option('show_post_fade_featuredpost', $default_options);

function show_post_fade_options_page(){
	global $wpdb;
	if (isset($_POST['update_options'])) {
		$options['show_featured'] = trim($_POST['show_featured'],'{}');
            //$options['show_category'] = trim($_POST['show_category'],'{}');
        $options['div_title'] = trim($_POST['div_title'],'{}');
		$options['more_text'] = trim($_POST['more_text'],'{}');
		$options['show_excerpt'] = trim($_POST['show_excerpt'],'{}');
		$options['excerpt_letters'] = trim($_POST['excerpt_letters'],'{}');
		
		$options['ignore_more'] = trim($_POST['ignore_more'],'{}');
        $options['num_posts'] = trim($_POST['num_posts'],'{}');
		$options['show_posts'] = trim($_POST['show_posts'],'{}');
		$options['show_pages'] = trim($_POST['show_pages'],'{}');
		
	
		$options['show_headline_only'] = trim($_POST['show_headline_only'],'{}');
		$options['headline_size'] = trim($_POST['headline_size'],'{}');
	
	
		$options['image_fade_out'] = trim($_POST['image_fade_out'],'{}');
		$options['image_fade_in'] = trim($_POST['image_fade_in'],'{}');
		$options['txt_fade_out'] = trim($_POST['txt_fade_out'],'{}');
		$options['txt_fade_in'] = trim($_POST['txt_fade_in'],'{}');







		$show_category = $_POST['show_category'];
            if (empty($show_category)) {
			$cats = "";
		} else {
			$cats = implode(" ", $show_category);
		}
		$options['show_category'] = $cats;

		update_option('show_post_fade_featuredpost', $options);
		echo '<div class="updated"><p>' . __('Options saved') . '</p></div>';
	} else {
		$options = get_option('show_post_fade_featuredpost');

		$show_category = explode(" ",$options['show_category']);
	}
	?>

<div class="wrap">
<font size="-2">
Documentation can be found here: <a href="http://wordpress.geegood.com/plugins/show-category-posts-fade-inout/" target="_blank">http://wordpress.geegood.com/plugins/show-category-posts-fade-inout/</a>
</font>
<hr />


  <h2><?php echo __('Show Posts Fade (in/out)'); ?></h2>
  <br />
  <form method="post" action="">
    <table align="left">
      <tr>
        <th align="left"><input type="checkbox" name="show_featured" value="show" <?php if ($options['show_featured'] == 'show') echo 'checked="checked"'; ?> />
          &nbsp;
          <?php _e('Show The Category Posts') ?>
          <br /></th>
      </tr>
      
      <tr>
        <td><?php _e('Number of posts to show: ') ?>
          <input type="text" name="num_posts" size="4" value="<?php if (is_numeric($options['num_posts'])) { echo $options['num_posts']; } else { echo '3'; } ?>" />
          <br /></td>
      </tr>
      
      
      
      
      <tr>
        <th align="left"><input type="checkbox" name="show_excerpt" value="Y" <?php if ($options['show_excerpt'] == 'Y') echo 'checked="checked"'; ?> />
          &nbsp;
          <?php _e('Show Excerpt') ?>
          <br /></th>
      </tr>
       <tr>
        <td><?php _e('Number of excerpt letters to show: ') ?>
          <input type="text" name="excerpt_letters" size="4" value="<?php if (is_numeric($options['excerpt_letters'])) { echo $options['excerpt_letters']; } else { echo '50'; } ?>" />
          <br /><font size="-2">If value set to 0 will use the &#60;&#33;&#45;&#45;&#77;&#111;&#114;&#101;&#45;&#45;&#62; tag in your post content.</font>
          </td>
      </tr>
      
      <tr>
        <td><hr />
          </td>
      </tr>
      
      
       <tr>
        <th align="left">
        
        <input type="checkbox" name="show_headline_only" value="Y" <?php if ($options['show_headline_only'] == 'Y') echo 'checked="checked"'; ?> />
        &nbsp; <?php _e('Show ONLY headlines? ') ?>
          </th>
      </tr>
      
      <tr>
        <td><?php _e('Current font size multiply by: ') ?><input type="text" name="headline_size" size="3" value="<?php if (is_numeric($options['headline_size'])) { echo $options['headline_size']; } else { echo '1.5'; } ?>" />
          
          <hr />
          </td>
      </tr>
    
    
    
      
      <tr>
        <td><?php _e('Fade IN image in milliseconds: ') ?>
          <input type="text" name="image_fade_in" size="4" value="<?php if (is_numeric($options['image_fade_in'])) { echo $options['image_fade_in']; } else { echo '800'; } ?>" />
          <br /></td>
      </tr>
       <tr>
        <td><?php _e('Fade OUT image in milliseconds: ') ?>
          <input type="text" name="image_fade_out" size="4" value="<?php if (is_numeric($options['image_fade_out'])) { echo $options['image_fade_out']; } else { echo '800'; } ?>" />
          <br /></td>
      </tr>
      <tr>
        <td><?php _e('Fade IN text in milliseconds: ') ?>
          <input type="text" name="txt_fade_in" size="4" value="<?php if (is_numeric($options['txt_fade_in'])) { echo $options['txt_fade_in']; } else { echo '800'; } ?>" />
          <br /></td>
      </tr>
      <tr>
        <td><?php _e('Fade OUT text in milliseconds: ') ?>
          <input type="text" name="txt_fade_out" size="4" value="<?php if (is_numeric($options['txt_fade_out'])) { echo $options['txt_fade_out']; } else { echo '800'; } ?>" />
          <br /></td>
      </tr>
      
      
      
      
      
      <tr>
        <td><?php _e('Featured Post Box Title: ') ?>
          <input type="text" name="div_title" size="30" value="<?php echo $options['div_title']; ?>" />
          <br /></td>
      </tr>
      <tr>
        <td><?php _e('Read more text: ') ?>
          <input type="text" name="more_text" size="30" value="<?php echo $options['more_text']; ?>" />
          <br /></td>
      </tr>
      
      <tr>
        <td><br />
          <input type="submit" name="update_options" value="<?php _e('Update') ?>"  style="font-weight:bold;" /></td>
      </tr>
     
      
      
      
    </table>
    <table border="0" align="left" width="300">
   <tr><td colspan="2" align="left"><strong>Show From Categories Marked Below</strong><br />
   <font size="-2">If you want then create a Category especially for Featured posts and tick it On below. Create the new category in Posts->Categories in the WP Posts menu.</font>
   </td></tr>
   <?php
   $categories = mysql_query("
	SELECT t.term_id, t.name
	FROM $wpdb->terms t
	LEFT JOIN $wpdb->term_taxonomy tax ON tax.term_id = t.term_id
	WHERE tax.taxonomy = 'category'
	ORDER BY t.name
	", $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);
	
   if ($categories && mysql_num_rows($categories) > 0) {
	while ($category = mysql_fetch_object($categories)) {
		echo '<tr><td align="center"><input type="checkbox" name="show_category[ ]" value="'.$category->term_id.'"';
		if (sizeof($show_category) > 0) if (in_array(strval($category->term_id),$show_category)) echo ' CHECKED';
            echo '></td><td>'.$category->name.'&nbsp;</td></tr>';  
	}
   }	
?>
    </table>
    <strong>Using it</strong><br />
    Since you at this screen you have successfully installed the plugin. Now enter below code where you want to display above categories:
    <p><font face="Lucida Console, Monaco, monospace" color="#000099">
  
    &#60;&#100;&#105;&#118;&#32;&#105;&#100;&#61;&#34;&#114;&#111;&#116;&#97;&#116;&#101;&#114;&#34;&#62;&#10;
    <br />
    &#60;&#63;&#112;&#104;&#112;&#32;
    <br /> &#105;&#102;&#32;&#40;&#102;&#117;&#110;&#99;&#116;&#105;&#111;&#110;&#95;&#101;&#120;&#105;&#115;&#116;&#115;&#40;&#39;&#115;&#104;&#111;&#119;&#95;&#112;&#111;&#115;&#116;&#115;&#95;&#102;&#97;&#100;&#101;&#39;&#41;&#41;&#32;&#123;
    <br />
    &#115;&#104;&#111;&#119;&#95;&#112;&#111;&#115;&#116;&#115;&#95;&#102;&#97;&#100;&#101;&#40;&#41;&#59;&#32;&#125;
    <br />
    &#63;&#62;
    <br />
    &#60;&#47;&#100;&#105;&#118;&#62;

    </font>
    <p>
    You can define the CSS id called "rotater" if you want.
    </p>
    Read the CSS you can change/alter for this plugin to look different (font, size etc.) here: <a href="http://wordpress.geegood.com/plugins/show-category-posts-fade-inout/" target="_blank">http://wordpress.geegood.com/plugins/show-category-posts-fade-inout/</a>
</font>
</p>
    
  </form>
  <iframe frameborder="0" scrolling="no" src="http://wordpress.geegood.com/wp/info/" width="100%" height="200">
      </iframe>
     
</div>
<?php	
}


// Get URL of first image in a post
function catch_that_image($post) {
//global $post, $posts;
$first_img = '';
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
$first_img = $matches [1] [0];

// no image found display default image instead
if(empty($first_img)){
$first_img = "/images/default.jpg";
}
return $first_img;
}



function show_posts_fade($PreFeature = '', $PostFeature = '', $AlwaysShow = false, $categoryID = 0, $NumberOfPosts = 0) {
   global $wpdb;
   

$options = get_option('show_post_fade_featuredpost');
?>

<script type="text/javascript">

jQuery(document).ready(function() {

//alert ('sdfsd');

var $active;
var image_fade_out =  <?php echo $options['image_fade_out']; ?>;
var image_fade_in =  <?php echo $options['image_fade_in']; ?>;
var txt_fade_out =  <?php echo $options['txt_fade_out']; ?>;
var txt_fade_in =  <?php echo $options['txt_fade_in']; ?>;
//alert (fade_out_image);
/*for (n=0; n<3; n++)
{
$('.topstory-box-img').eq(n).hide().css({opacity: 0.0});
$('.topstory-box-txt').eq(n).hide().css({opacity: 0.0});
}
*/

jQuery('.topstory').hide();
jQuery('.topstory-box-img').hide();
jQuery('.topstory-box-txt').hide();

$active = jQuery('.topstory').eq(0);
$active.addClass('active');
//$active.fadeTo(500,1);
//var $oldactive =  $('.topstory.active');
jQuery('.topstory').eq(0).show();
jQuery('.topstory-box-img').eq(0).show().fadeTo(image_fade_in,1);
jQuery('.topstory-box-txt').eq(0).show().fadeTo(txt_fade_in,1);

jQuery('.topstory').eq(1).hide();
jQuery('.topstory').eq(2).hide();

	//$oldactive.fadeTo(1200,0);
	//Paging + Slider Function
	rotate = function(){	
		//var triggerID = $active.attr("rel") - 1; //Get number of times to slide
		//var image_reelPosition = triggerID * imageWidth; //Determines the distance the image reel needs to slide
		var $temp = $active;
		
		
		var tmp = $temp.index() - 1;
		//alert (tmp);
		jQuery('.topstory-box-img').eq(tmp).fadeTo (image_fade_out,0,function() {
			
									$temp										
										.removeClass('active')
										.addClass('last-active');
										
										
										jQuery('.topstory-box-txt').eq(tmp).fadeTo (txt_fade_out,0,function() {
													$temp.hide();
													});
																			
										$active = $active.next();
										if ( $active.length === 0) $active = jQuery('.topstory:first'); 
																			
										$active.addClass('active'); 
										$active.show();
										var tmp2 = $active.index() -1;
										jQuery('.topstory-box-img').eq(tmp2).fadeTo (image_fade_in,1);
										jQuery('.topstory-box-txt').eq(tmp2).fadeTo (txt_fade_in,1);
							        });
		
		
		
		
	}; 
	
	//Rotation + Timing Event
	rotateSwitch = function(){		
		play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
			//$active = $('.topstory.active').next();
			//if ( $active.length === 0) { //If paging reaches the end...
				//$active = $('.topstory:first'); //go back to first
			//}
			rotate(); //Trigger the paging and slider function
		}, 5500); //Timer speed in milliseconds (3 seconds)
	};
	rotateSwitch(); //Run function on launch
	
	
	//On Hover
	jQuery(".topstory").hover(function() {
		clearInterval(play); //Stop the rotation
	}, function() {
		
		rotateSwitch(); //Resume rotation
		//rotate();
	});	
	
	//On Click
	//$(".paging a").click(function() {	
	//	$active = $(this); //Activate the clicked paging
		//Reset Timer
	//	clearInterval(play); //Stop the rotation
		//rotate(); //Trigger rotation immediately
		//rotateSwitch(); // Resume rotation
		//return false; //Prevent browser jump to link anchor
//	});	
	
});
</script>



<?php

   //wp_enqueue_script("jquery");
  // wp_deregister_script( 'jquery' );
   // wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');

  
   $options = get_option('show_post_fade_featuredpost');
   
   if (!$AlwaysShow) { if ($options['show_featured'] != 'show') return; }
   
   if ($categoryID == 0) {
	$show_category = explode(" ",$options['show_category']);
      if (sizeof($show_category) == 0) return;
   } else {
      if (!is_numeric($categoryID)) return;
      $show_category = explode(" ",$categoryID);
   }
   $sqlcat = "( ";
   $count = 0;
   foreach ($show_category as $cat) {
      if ($count > 0) $sqlcat = $sqlcat." OR ";
      $sqlcat = $sqlcat."$wpdb->term_taxonomy.term_id = ".$cat;
      $count = $count + 1;
   }
   $sqlcat = $sqlcat." )";

   if (!is_numeric($options['num_posts'])) {
	$num_posts = '1';
   } else {
      $num_posts = $options['num_posts'];
   } 
   
   
   
   
   if (!is_numeric($options['image_fade_out'])) {
	$image_fade_out = 800;
   } else {
      $image_fade_out = $options['image_fade_out'];
   }
   
   
   
   
   
   
   
   if ($NumberOfPosts > 0) $num_posts = $NumberOfPosts;

   if (empty($options['more_text'])) {
      $more_text = 'Read more &rarr;';
   } else {
      $more_text = $options['more_text'];
   }

   $sqlposts = '';   //  shows pages and posts
   if ($options['show_posts'] != 'Y' && $options['show_pages'] != 'Y')  {   // not checked, default to show posts only
   	$sqlposts = " AND $wpdb->posts.post_type = 'post' ";
   } else {
	if ($options['show_posts'] == 'Y' && $options['show_pages'] != 'Y')  {   // show only posts
	   	$sqlposts = " AND $wpdb->posts.post_type = 'post' ";
	} elseif ($options['show_posts'] != 'Y' && $options['show_pages'] == 'Y')  {   // show only pages
	   	$sqlposts = " AND $wpdb->posts.post_type = 'page' ";
	}  // if both checked do nothing and pages and posts shown by default
   }

   $div_title = $options['div_title'];
   if (empty($div_title)) $div_title = "Featured Post";

   $posts = mysql_query("
		SELECT * FROM $wpdb->posts
		LEFT JOIN $wpdb->term_relationships ON
		($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		LEFT JOIN $wpdb->term_taxonomy ON
		($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
		WHERE $wpdb->posts.post_status = 'publish'".$sqlposts."
		AND $wpdb->term_taxonomy.taxonomy = 'category'
		AND ".$sqlcat." ORDER BY RAND()
		LIMIT ".$num_posts, $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);
	
   if ($posts && mysql_num_rows($posts) > 0) {
	while ($thepost = mysql_fetch_object($posts)) {
		$myID = $thepost->ID;

   		//if ($myID == 0) return;

		$thepost = get_post($myID);
		setup_postdata($thepost);
?>
<div class="topstory">
<?php
   		echo $PreFeature."\n<div class=\"featuredpost\">\n";
   		
   		/*echo "<h2><a href=\"";
   		echo get_permalink($myID);
   		echo "\" title=\"";
   		echo apply_filters('the_title', $thepost->post_title);
   		echo "\">";
   		echo apply_filters('the_title', $thepost->post_title);
   		echo "</a></h2>\n";*/
		echo '<div class="topstory-box-img">';
		if (has_post_thumbnail())
		{
			
			echo "<a href=\"";
					echo get_permalink($myID);
					echo "\" title=\"";
					echo apply_filters('the_title', $thepost->post_title);
					echo "\">";
		echo '<img src="' . catch_that_image($thepost) .'">';
		//the_post_thumbnail();//array(320,320));
		echo "</a>\n";
		}
		echo "</div>";

		?>
<div class="topstory-box-txt">
<?php echo "<h3>".$div_title."</h3>\n"; ?>

  <h1><?php echo '<span style="font-size:' . $options['headline_size'] . 'em !important; line-height:0.9em !important; ">'; ?><a href="<?php echo get_permalink($myID); ?>" title="<?php the_title_attribute(); ?>"><?php echo apply_filters('the_title', $thepost->post_title); ?></a></span></h1>
   <div class="mousetxt">Mouse over - stop slideshow - out continue.</div>
  <?php     
  
  				$excerpt_letters =  $options['excerpt_letters'];


				
				
				//echo "<a href=\"";
				//echo get_permalink($myID);
   				//echo "\" title=\"";
   				//echo apply_filters('the_title', $thepost->post_title);
				//echo "\"></a>";
			
			
			
			//$options['show_headline_only'] = trim($_POST['show_headline_only'],'{}');
			//$options['headline_size'] = trim($_POST['headline_size'],'{}');	
	//echo $options['show_headline_only'];
			if ($options['show_headline_only'] != 'Y')
			{
				echo "<a href=\"";
				echo get_permalink($myID);
   				echo "\" title=\"";
   				echo apply_filters('the_title', $thepost->post_title);
				echo "\">";
  				if ($excerpt_letters > 0)
  						{
							$tmp = $thepost->post_content;
							$tmp = preg_replace("/\[caption.*\[\/caption\]/", '', $tmp);
							//echo $tmp;
							$tmp = substr($tmp,0,$excerpt_letters);
							$tmp = strip_tags ($tmp);
							echo $tmp;
	      					//the_content('');
                        } else {
							$tmp = $thepost->post_content;
							$tmp = preg_replace("/\[caption.*\[\/caption\]/", '', $tmp);
							$tmp = substr($tmp,0,strpos($thepost->post_content,"<!--more-->"));
							$tmp = strip_tags ($tmp);
							echo $tmp;
							//echo apply_filters('the_content', substr($thepost->post_content,0,$moreposition));
                        }
				echo "</a>\n";			
			}
  			
			
			
  			//echo apply_filters('the_content', substr($tmp,0,300)); 
			//echo apply_filters('the_excerpt', $thepost->post_excerpt) . '...';
?>

<?php	
		
   		
   			echo "<a href=\"";
   			echo get_permalink($myID);
   			echo "\" title=\"";
   			echo apply_filters('the_title', $thepost->post_title);
			echo "\">".$more_text."</a>\n";
   			echo "</div></div></div>\n";
	}   // end while
   }  // else no posts found
}



?>