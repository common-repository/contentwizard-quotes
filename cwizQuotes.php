<?php
/** 
 * Plugin Name: ContentWizard Quotes
 * Plugin URI: https://contentwizard.ch/
 * Description: Plugin which displays ContentWizard quotes in a side column widget, allowing a choice of five languages (En,De,It,Fr,Es)
 * Version: 1.0
 * Author: ContentWizard.ch (Andrew Bone)
 * Author URI: https://contentwizard.ch/
 * License: GPLv2
 */

register_deactivation_hook( __FILE__, 'cwizQuotes_deactivate' );
function cwizQuotes_deactivate() { //no special action required in this version
}

register_activation_hook( __FILE__, 'cwizQuotes_install' );

function cwizQuotes_install() {
	$cwizQuotes_options_arr = array( 'default_lang' => 'en' );
	update_option ( 'cwizQuotes_options', $cwizQuotes_options_arr);
}

add_action( 'init', 'cwizQuotes_init' );

function cwizQuotes_init() {

}

defined( 'ABSPATH' ) or die( 'Access denied' );

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

add_action( 'widgets_init', 'register_cwizQuotes_widget' );

function register_cwizQuotes_widget() {
	register_widget( 'cwizQuotes_widget' );
}

class cwizQuotes_widget extends WP_Widget {
    function __construct() {
        $widget_ops = array(
            'classname'   => 'cwizQuotes_widget_class',
            'description' => __( 'Displays ContentWizard Quotes', 'cwizQuotes-plugin' ) );
        parent::__construct( 'cwizQuotes_widget', 'cwizQuotes Widget', $widget_ops );
    }
/* NB: colors must be given as 6-figure hexamdecimal t be accepted by the color picker */
    function form( $instance ) { 
        $defaults = array(
            'widget_language' => 'Auto language detection',
            'cQ_length'  => 400
			 );
        $instance = wp_parse_args( (array) $instance, $defaults );    
        $widget_language = $instance['widget_language']; if(!isset($widget_language) || $widget_language == '') { $widget_language = ''; }
        $cQ_length = $instance['cQ_length'];

?>

<p class="clear_both"><label for="widget_language">Language: &nbsp;&nbsp; (this setting is optional and will override site language settings) </label><select class="submitValue" name="<?php echo $this->get_field_name( 'widget_language' ); ?>" id="font">
<option value="<?php echo esc_attr( $widget_language ); ?>" ><?php echo esc_attr( $widget_language ); ?></option>
<option value="English" >English</option><option value="German" >Deutsch</option><option value="Italian" >Italiano</option><option value="French" >Fran&ccedil;ais</option><option value="Spanish" >Espa&ntilde;ol</option><option value="" >Auto language detection</option>     
</select></p>

<p>Max length (no. characters): <input class="widefat" name="<?php echo $this->get_field_name( 'cQ_length' ); ?>" type="text" value="<?php echo esc_attr( $cQ_length ); ?>" /></p>

       <?php
    }
    //save widget settings
    function update( $new_instance, $old_instance ) { 
        $instance = $old_instance;
        $instance['widget_language'] = sanitize_text_field( $new_instance['widget_language'] );
        $instance['cQ_length']  = absint( $new_instance['cQ_length'] );

        return $instance; 
    }

   function widget($args, $instance) { 
		include_once('inc/select_lang.inc.php');

		if(!isset($before_widget)) { $before_widget = ''; }
		if(!isset($after_widget)) { $after_widget = ''; }
		
		if(isset($instance['cQ_length'])) { $cQ_length = $instance['cQ_length']; } else { $cQ_length = 400; }
		
		$get_quote = 'https://contentwizard.ch/quotes/quotes.php?qlang='.$qlang.'&length='.$cQ_length;
		echo $before_widget; 

?>


<aside id="cwizQuotes_display" class="widget cwiz-hidden">
<h3 class='widget-title'><?= $cQ_title.' '.$bgg; ?></h3>
<div id="cwizQuotes_content"></div>
</aside>
<script type='text/javascript' src="<?= $get_quote; ?>"></script>
	
<?php 
		echo $after_widget; 
?>

<?php
		extract($args);
    }
}


// register jquery and style on initialization
add_action('init', 'cwizQuotes_register_script');
function cwizQuotes_register_script() {
    wp_register_style( 'cwizQuotes', plugins_url('/css/quotes-cwiz.css', __FILE__), false, '1.0.0', 'all');
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'ceq_enqueue_style');

function ceq_enqueue_style(){
   wp_enqueue_style( 'cwizQuotes' );
}
 
 ?>