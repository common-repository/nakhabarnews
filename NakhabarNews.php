<?php
/*
 * Plugin Name: Nakhabar News
 * Plugin URI: http://nakhabar.com/nakhabarnews
 * Description: Widget to Nakhabar news text vertically.
 * Version: 1.2
 * Author: Aidev Ab
 * Author URI: http://nakhabar.com
 * License: GPL2
 */

/*  Copyright 2015  Nakhabar  (email: nakhabar.news@gmail.com)
*/


function nn_add_menu_icons_styles(){
?>
	<style type="text/css">
	#adminmenu #toplevel_page_nn_setting_page_slug div.wp-menu-image:before { content: "\f111"; }
	</style>
<?php
}

add_action( 'admin_head', 'nn_add_menu_icons_styles' );
add_action( 'admin_menu', 'nn_menu_page' );
add_action( 'admin_init', 'nn_setting_options' );

function nn_setting_options() {
        //register our settings
        register_setting( 'baw-settings-group', 'nn_direction' );
        register_setting( 'baw-settings-group', 'nn_speed' );
	register_setting( 'baw-settings-group', 'nn_height' );

}

function nn_menu_page(){
    add_menu_page( 'NakhabarNews', 'NakhabarNews',
        'manage_options',
        'nn_setting_page_slug',
        'nn_setting_page',
        plugins_url( 'icon.png', __FILE__  ), 6 );
}

function nn_setting_page(){
?>

<div class="wrap">
<h2>Scrolling News widget settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'baw-settings-group' ); ?>
    <?php do_settings_sections( 'baw-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Direction</th>
        <td><input type="text" name="nn_direction" value="<?php echo esc_attr( get_option('nn_direction') ); ?>" />
                        "up" or "down". The default up
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Speed</th>
        <td><input type="text" name="nn_speed" value="<?php echo esc_attr( get_option('nn_speed') ); ?>" />
                        "4" or "6". The default is 5, fastest = 1, slowest = 9
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Height</th>
        <td><input type="text" name="nn_height" value="<?php echo esc_attr( get_option('nn_height') ); ?>" />
                        Please enter number between 100 to 600. The default is 250px.
        </td>
        </tr>

    </table>

    <?php submit_button(); ?>

</form>
</div>

<ul>
        <li>Salam, Wordpress users, this plugin will provide you with latest hashtag and news of today</li>
        <li>Request you to provide review on this <a href="https://wordpress.org/plugins/NakhabarNews/">plugin</a></li>
        <li>Please visit us at <a href="http://NaKhabar.com">Nakhabar.com</a></li>
        <li>You can also email me at nakhabar.news@gmail.com</li>

</ul>

<?php

}




class nn_NakhabarNews extends WP_Widget{

    function __construct(){
            $params = array(
                    'description'=>'مهمترین هشتگ ها و خبرهای روز جمع آوری شده توسط روباتهای نخبر',
                    'name'=>'NakhabarNews'
            );
            parent::__construct('nn_NakhabarNews','',$params);
    }

    public function form($instance){
            extract($instance);

            ?>
            <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
                    <input
                            class="widefat"
                            id="<?php echo $this->get_field_id('title'); ?>"
                            name="<?php echo $this->get_field_name('title'); ?>"
                            placeholder="سر خط مهمترین خبرها"
                            value="<?php if( isset($title) ) echo esc_attr($title); ?>"
                    >
            </p>

            <p>
					<label for="<?php echo $this->get_field_id('description'); ?>">Description:</label>
						<textarea
							class="widefat"
							rows="5"
							id="<?php echo $this->get_field_id('description'); ?>"
							name="<?php echo $this->get_field_name('description'); ?>"
							placeholder="منبع نخبر دات کام"
						><?php if( isset($description) ) echo esc_attr($description); ?></textarea>
	    </p>


            <p>
                    <label for="<?php echo $this->get_field_id('searchedName'); ?>">Title</label>
                    <input
                            class="widefat"
                            id="<?php echo $this->get_field_id('searchedName'); ?>"
                            name="<?php echo $this->get_field_name('searchedName'); ?>"
                            placeholder="نظرات شما درباره اخبار روز"
                            value="<?php if( isset($searchedName) ) echo esc_attr($searchedName); ?>"
                    >
            </p>

            <p>
                    <input class="checkbox" type="checkbox" <?php checked($instance['item1'], 'on'); ?> id="<?php echo $this->get_field_id('item1'); ?>"
                                   name="<?php echo $this->get_field_name('item1'); ?>" />
                    <label for="<?php echo $this->get_field_id('item1'); ?>">هشتگ ها</label>
                    <Br>
                    <input class="checkbox" type="checkbox" <?php checked($instance['item2'], 'on'); ?> id="<?php echo $this->get_field_id('item2'); ?>"
                           name="<?php echo $this->get_field_name('item2'); ?>" />
                    <label for="<?php echo $this->get_field_id('item2'); ?>">مهم ترین اخبار کوتاه</label>
                    <Br>
                    <input class="checkbox" type="checkbox" <?php checked($instance['item3'], 'on'); ?> id="<?php echo $this->get_field_id('item3'); ?>"
                           name="<?php echo $this->get_field_name('item3'); ?>" />
                    <label for="<?php echo $this->get_field_id('item3'); ?>">آخرین نظرات شما درباره اخبار روز</label>
                    <!--?php  echo print_r(array_values($instance)); ?-->
            </p>

            <?php

    }


    public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['item1'] = $new_instance['item1'];
            $instance['item2'] = $new_instance['item2'];
            $instance['item3'] = $new_instance['item3'];
			$instance['title'] = $new_instance['title'];
			$instance['description'] = $new_instance['description'];
			$instance['searchedName'] = $new_instance['searchedName'];
            return $instance;
    }

    function fileExists($path){
         return (@fopen($path,"r")==true);
    }

    public function widget($args,$instance){

            if(get_option( 'nn_direction') == "down"){
                    $direction = 'down';
            }else{
                    $direction = 'up';
            }

            if(get_option('nn_speed') == ""){
                    $speed = 5000;
            }else{
                    $speed = get_option('nn_speed') *1000;
            }
			
			
	    if(get_option('nn_height') == ""){
                    $nn_height = '250';
            }else{
            	$nn_height= get_option('nn_height');
            }
        	$nn_height = "height: ".$nn_height.'px;';

            extract($args);
            extract($instance);
			date_default_timezone_set('Europe/Stockholm');
            $today = date("Y_m_d");
            $yesterday = date('Y_m_d',strtotime("-1 days"));
			$hour = intval(date("H"));
            $upload_dir = wp_upload_dir();
	    $no_update = TRUE;
			

           if ($item1=='on') {
                $filename = 'short_hashtag_'.$today.'.html';
                $address = $upload_dir['path'].'/'.$filename;
				if($hour==9 or $hour==12 or $hour==14){
					if (file_exists($address)) {
 					    $fmodif=intval(date("H", filemtime($filename)));
						if($fmodif< $hour){
							$no_update =FALSE;
						}
					}
				}
			
                if(file_exists($address) and $no_update){
                        $to_show = file_get_contents($address);
                }
                else{
                        $address_net = 'http://nakhabar.com/wp-content/loading/json/short_hashtag_'.$today.'.html';
                        if (!file_exists($address_net)){
                            $address_net = 'http://nakhabar.com/wp-content/loading/json/short_hashtag_'.$yesterday.'.html';
                        }

                        file_put_contents($address, $address_net);
                        copy($address_net, $address);
                        $fone = fopen($address,"r") or die("no news sorry") ;
                        $to_show = fread($fone, filesize($address));
                        fclose($fone);
                }
                $description = $to_show.$description;
           };


           if ($item2=='on') {
				$filename = 'short_description_'.$today.'.html';
                $address = $upload_dir['path'].'/'.$filename;
				if($hour==9 or $hour==12 or $hour==14){
					if (file_exists($address)) {
 					    $fmodif=intval(date("H", filemtime($filename)));
						if($fmodif< $hour){
							$no_update =FALSE;
						}
					}
				}
			
                if(file_exists($address) and $no_update){
                        $to_show = file_get_contents($address);
                }
                else{
						$address_net = 'http://nakhabar.com/wp-content/loading/json/short_description_'.$today.'.html';
                                                if (!file_exists($address_net)){
                            $address_net = 'http://nakhabar.com/wp-content/loading/json/short_description_'.$yesterday.'.html';
                        }
                         file_put_contents($address, $address_net);
                        copy($address_net, $address);
                        $ftwo = fopen($address,"r") or die("no news sorry") ;
                        $to_show = fread($ftwo, filesize($address));
                        fclose($ftwo);
                }
				if ($item1=='on') {
                	$description = $description.$to_show;
				}else{
					$description = $to_show.$description;
				}
           };

           if ($item3=='on') {
                $filename = 'Names_hashtags_search.html';
                $address = $upload_dir['path'].'/'.$filename;
                                if($hour==9 or $hour==13 or $hour==17 or $hour==20 or $hour==23){
                                        if (file_exists($address)) {
                                            $fmodif=intval(date("H", filemtime($filename)));
                                            if($fmodif< $hour){
                                                 $no_update =FALSE;
                                            }
                                        }
                                }
                if(file_exists($address) and $no_update){
                        $to_show = file_get_contents($address);
                }
                else{
                        $address_net = 'http://nakhabar.com/wp-content/loading/json/'.$filename;
                         file_put_contents($address, $address_net);
                        copy($address_net, $address);
                        $fthree = fopen($address,"r") or die("no news sorry") ;
                        $to_show = fread($fthree, filesize($address));
                        fclose($fthree);
                }
                 
                      
                 if ($item1=='on' or $item2=='on') {
                     $description = $description.$to_show;
                     }else{
                          $description = $to_show.$description;
                 }
           };

            echo $before_widget;
            echo $before_title . $title . $after_title;
			
	echo '<div class="marquee ver" style="'.$nn_height.'" data-direction='.$direction.' data-duration='.$speed.' data-pauseOnHover="true">'.$description.'</div>' ;
            echo $after_widget;

    }

}

add_action('widgets_init','nn_register_scroll');
function nn_register_scroll(){

        register_widget('nn_NakhabarNews');
}

function nn_admin_load_js(){
		wp_enqueue_script('jquery_marquee', plugins_url( '/js/jquery.marquee.min.js', __FILE__ ),array( 'jquery' ) );
		wp_enqueue_style('jquery_marquee_style', plugins_url('/js/jquery.marquee.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'nn_admin_load_js');
