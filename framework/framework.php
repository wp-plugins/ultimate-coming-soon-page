<?php
/**
 * SeedProd Framework - Inspired by Yoast's Plugins and WooThemes Framework
 *
 * @package WordPress
 * @subpackage Ultimate_Coming_Soon_Page
 * @since 0.1
 */
if (!class_exists('SeedProd_Framework')) {
	class SeedProd_Framework {
	
        /**
         * Define the Version of the Plugin
         */
        public $plugin_version = '';
        public $plugin_type = ''; // free,lite and pro
        public $plugin_name = '';
        public $plugin_support_url = '';
        public $plugin_short_url = '';
        public $plugin_seedprod_url = '';
        public $plugin_donate_url = '';
        public $plugin_official_url = '';
        private $framework_version = '0.1';

        /**
         * Define if we are deploying a theme and add the theme params
         */
        public $deploy_theme = 0;
        public $deploy_theme_name = array('template' =>'', 'stylesheet' => '');

        /**
         * Global we set in seedprod_admin_enqueue_scripts and use in create_menu
         */
        public $pages = array();

        /**
         *  Define the menus that will be rendered.
         *  Do not replace callback function.
         */
        public $menu = array();
        
        /**
         *  Define options, sections and fields
         */
        public $options = array();
	
    	/**
    	 * Load Hooks
    	 */
    	function __construct() {
    	    add_action('admin_enqueue_scripts', array(&$this,'admin_enqueue_scripts'));
    	    add_action('admin_menu',array(&$this,'create_menu'));
    	    add_action('admin_init', array(&$this,'set_settings'));
    	}
    	
    	/**
         * Set the base url to use in the plugin
         *
         * @since  0.1
         * @return string
         */
    	function base_url(){
            return plugins_url('',dirname(__FILE__));
        }
    	    
	
        /**
         * Properly enqueue styles and scripts for our theme options page.
         *
         * This function is attached to the admin_enqueue_scripts action hook.
         *
         * @since  0.1
         * @param string $hook_suffix The name of the current page we are on.
         */
        function admin_enqueue_scripts( $hook_suffix ) {
            if(!in_array($hook_suffix, $this->pages))
                return;
            wp_enqueue_script('dashboard');
        	wp_enqueue_script( 'seedprod_framework', plugins_url('framework.js',__FILE__), array( 'jquery','media-upload','thickbox','farbtastic' ), $this->plugin_version );
        	wp_enqueue_style( 'seedprod_framework', plugins_url('framework.css',__FILE__), false, $this->plugin_version );
        	wp_enqueue_script( 'seedprod_plugin', plugins_url('inc/js/admin-script.js',dirname(__FILE__)), array( 'jquery','media-upload','thickbox','farbtastic' ), $this->plugin_version );
        	wp_enqueue_style( 'seedprod_plugin', plugins_url('inc/css/admin-style.css',dirname(__FILE__)), false, $this->plugin_version );
        	wp_enqueue_style('thickbox');
            wp_enqueue_style('farbtastic'); 
        }

        /**
         * Creates WordPress Menu pages from an array in the config file.
         *
         * This function is attached to the admin_menu action hook.
         *
         * @since 0.1
         */
        function create_menu(){
            foreach ($this->menu as $v) {
                $this->pages[] = call_user_func_array($v['type'],array($v['page_name'],$v['menu_name'],$v['capability'],$v['menu_slug'],$v['callback'],$v['icon_url']));
            }
    
        }

        /**
         * Render the option pages.
         *
         * @since 0.1
         */
        function option_page() {
            $page = $_REQUEST['page'];
        	?>
        	<div class="wrap seedprod">
        	    <?php screen_icon(); ?>
        		<h2><?php echo $this->plugin_name; ?> </h2>
        		<?php settings_errors(); ?> 
        		<div id="poststuff" class="metabox-holder has-right-sidebar">
                    <div id="side-info-column" class="inner-sidebar">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div class="postbox support-postbox">
                                <div class="handlediv" title="Click to toggle"><br /></div>
                				<h3 class="hndle"><span>Plugin Support</span></h3>
                				<div class="inside">
                					<div class="support-widget">
                					<p>
                					   Got a Question, Idea, Problem or Praise?
                					</p>
                					<ul>
                					    <li>&raquo; <a href="<?php echo (empty($this->plugin_support_url) ? 'http://seedprod.com/support/' : $this->plugin_support_url) ?>">Support Request</a></li>
                				    </ul>
                					
                					</div>
                				</div>
                            </div>
                            <?php if($this->plugin_type != 'pro'){ ?>
                            <div class="postbox like-postbox">
                                <div class="handlediv" title="Click to toggle"><br /></div>
                				<h3 class="hndle"><span>Show Some Love</span></h3>
                				<div class="inside">
                					<div class="like-widget">
                					<p>Like this plugin? Show your support by:</p>
                					<ul>
                					    <li>&raquo; <a href="<?php echo (empty($this->plugin_donate_url) ? 'http://seedprod.com/donate/' : $this->plugin_donate_url) ?>">Donate To It</a></li>
                					    <li>&raquo; <a href="<?php echo "http://twitter.com/share?url={$this->plugin_seedprod_url}&text=Check out this awesome WordPress Plugin I'm using, '{$this->plugin_name}' by SeedProd {$this->plugin_short_url}"; ?>">Tweet It</a></li>
                					    <?php if(!empty($this->plugin_official_url)){ ?>
                					    <li>&raquo; <a href="<?php echo $this->plugin_official_url ?>">Rate It</a></li>
                					    <?php } ?>
                					</ul>
                					</div>
                				</div>
                            </div>
                            <?php } ?>
                            <div class="postbox rss-postbox">
                                <div class="handlediv" title="Click to toggle"><br /></div>
                				<h3 class="hndle"><span>SeedProd Blog</span></h3>
                				<div class="inside">
                					<div class="rss-widget">
                					<?php
                					wp_widget_rss_output(array(
                					   'url' => 'http://seedprod.com/feed/',
                					   'title' => 'SeedProd Blog',
                					   'items' => 3,
                					   'show_summary' => 0,
                					   'show_author' => 0,
                					   'show_date' => 1,
                					));
                					?>
            					    <ul>
                					    <li>&raquo; <a href="http://seedprod.com/subscribe/">Subscribe by Email</a></li>
                				    </ul>
                					</div>
                				</div>
                            </div>
                            
                        </div>
                    </div>
                    <div id="post-body">
                        <div id="post-body-content" >
                            <div class="meta-box-sortables ui-sortable">
                                <form action="options.php" method="post">
                                <?php
                                foreach ($this->options as $v) {
                                    if($v['menu_slug'] == $page){
                                        switch ($v['type']) {
                                            case 'setting':
                        				        settings_fields($v['id']);
                        				        break;
                        				    case 'section':
                        				        echo '<div class="postbox seedprod-postbox"><div class="handlediv" title="Click to toggle"><br /></div>';
                                        		$this->seedprod_do_settings_sections($v['id']);
                                    		    echo '</div>';
                                    		    break;
                        		    
                        		        }
                		            }
                                }
                                ?>
                        		<input name="Submit" type="submit" value="Save Changes" class="button-primary"/>
                        	    </form>
                            </div>
                        </div>
                    </div>
                </div>
        	</div>	
        	<?php
        }

        /**
         * Create the settings options, sections and fields via the WordPress Settings API
         *
         * This function is attached to the admin_init action hook.
         *
         * @since 0.1
         */
        function set_settings(){
            foreach ($this->options as $k) {
                switch ($k['type']) {
                    case 'setting':
                        if(empty($k['validate_function'])){
                	        $k['validate_function'] = array(&$this,'validate_machine');
                	    }
                    	register_setting(
                    		$k['id'],
                    		$k['id'],
                    		$k['validate_function']
                    	);
                    	break;
                	case 'section':
                	    if(empty($k['desc_callback'])){
                	        $k['desc_callback'] = array(&$this,'section_dummy_desc');
                	    }else{
                	        $k['desc_callback'] = array(&$this, $k['desc_callback']);
                	    }
                    	add_settings_section(
                    		$k['id'],
                    		$k['label'],
                    		$k['desc_callback'],
                    		$k['id']
                    	);
                    	break;
                	default:
                    	if(empty($k['callback'])){
                	        $k['callback'] = array(&$this,'field_machine');
                	    }
                    	add_settings_field(
                    		$k['id'],
                    		$k['label'],
                    		$k['callback'],
                    		$k['section_id'],
                    		$k['section_id'],
                    		array('id' => $k['id'], 
                    		'desc' => $k['desc'],
                    		'setting_id' => $k['setting_id'], 
                    		'class' => $k['class'], 
                    		'type' => $k['type'],
                    		'default_value' => $k['default_value'],
                    		'option_values' => $k['option_values'] )
                    	);
        	    }
            }
        }

        /**
         * Create a field based on the field type passed in.
         *
         * @since 0.1
         */
        function field_machine($args) {
            extract($args);
        	$options = get_option( $setting_id );
        	switch($type){
        	    case 'textbox':
        	        echo "<input id='$id' class='".(empty($class) ? 'regular-text' : $class)."' name='{$setting_id}[$id]' type='text' value='".(empty($options[$id]) ? $default_value : $options[$id])."' />
        	        <br><small class='description'>".(empty($desc) ? '' : $desc)."</small>";
        	        break;
                case 'image':
        	        echo "<input id='$id' class='".(empty($class) ? 'regular-text' : $class)."' name='{$setting_id}[$id]' type='text' value='".(empty($options[$id]) ? $default_value : $options[$id])."' />
        	        <input id='{$id}_upload_image_button' class='button-secondary' type='button' value='Media Image Library' />
        	        <br><small class='description'>".(empty($desc) ? '' : $desc)."</small>
        	        <script type='text/javascript'>
        	        jQuery(document).ready(function($) {

                    	var formfield_{$id} = null;

                    	$('#{$id}_upload_image_button').click(function() {
                    		$('html').addClass('Image');
                    		formfield_{$id} = $('#{$id}').attr('name');
                    		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
                    		return false;
                    	});

                    	window.original_send_to_editor = window.send_to_editor;
                    	window.send_to_editor = function(html){
                    	    var fileurl_{$id};

                    		if (formfield_{$id} != null) {
                    			fileurl_{$id} = $('img',html).attr('src');

                    			$('#{$id}').val(fileurl_{$id});

                    			tb_remove();

                    			$('html').removeClass('Image');
                    			formfield_{$id} = null;
                    		} else {
                    			window.original_send_to_editor(html);
                    		}
                    	};

                    });
        	        </script>
        	        ";
        	        break;
        	    case 'select':
            	    echo "<select id='$id' class='".(empty($class) ? '' : $class)."' name='{$setting_id}[$id]'>";
            	    foreach($option_values as $k=>$v){
            	        if(preg_match("/optgroupend/i",$k)){
            	            echo "</optgroup>";
            	        }else{
            	            if(preg_match("/optgroup/i",$k)){
                	            echo "<optgroup label='$v'>";
                	        }else{

                	            if(preg_match("/empty/i",$k) && empty($default_value)){             
                	                echo "<option value=''>$v</option>";
                	            }else{
            	                    echo "<option value='$k' ".((preg_match("/empty/i",$options[$id] || isset($options[$id]) === false) ? $default_value : $options[$id]) == $k ? 'selected' : '').">$v</option>";
        	                    }
        	                }
        	            }

            	    }
            	    echo "</select>
                    <br><small class='description'>".(empty($desc) ? '' : $desc)."</small>";
                    break;
        	    case 'textarea':
                    echo "<textarea id='$id' class='".(empty($class) ? '' : $class)."' name='{$setting_id}[$id]'>".(empty($options[$id]) ? $default_value : $options[$id])."</textarea>
        	        <br><small class='description'>".(empty($desc) ? '' : $desc)."</small>";
        	        break;
        	    case 'radio':
        	        foreach($option_values as $k=>$v){
        	            echo "<input type='radio' name='{$setting_id}[$id]' value='$k'".((empty($options[$id]) ? $default_value : $options[$id]) == $k ? 'checked' : '')."  /> $v<br/>";
                    }
        	        echo "<small class='description'>".(empty($desc) ? '' : $desc)."</small>";
        	        break;
        	    case 'checkbox':
        	        $count = 0;
        	        foreach($option_values as $k=>$v){
        	            echo "<input type='checkbox' name='{$setting_id}[$id][]' value='$k'".(in_array($k,(empty($options[$id]) ? (empty($default_value) ? array(): $default_value) : $options[$id])) ? 'checked' : '')."  /> $v<br/>";
                        $count++;
                    }
        	        echo "<small class='description'>".(empty($desc) ? '' : $desc)."</small>";
        	        break;
        	    case 'color':
        	        echo "
            	        <input id='$id' type='text' name='{$setting_id}[$id]' value='".(empty($options[$id]) ? $default_value : $options[$id])."' />
                        <a href='#' class='pickcolor' id='$id-example'></a>
                        <input type='button' class='pickcolor button-secondary' value='Select Color'>
                        <div id='$id-colorPickerDiv' style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>
                        <br />
                        <small class='description'>".(empty($desc) ? '' : $desc)."</small>
                        <style type='text/css'>
                        #$id-example {
                        	-moz-border-radius: 4px;
                        	-webkit-border-radius: 4px;
                        	border-radius: 4px;
                        	border: 1px solid #dfdfdf;
                        	margin: 0 7px 0 3px;
                        	padding: 4px 14px;
                        }
                        </style>
                        <script type='text/javascript'>
                        var farbtastic_$id;

                        (function($){
                        	var pickColor_$id = function(a) {
                        		farbtastic_$id.setColor(a);
                        		$('#$id').val(a);
                        		$('#$id-example').css('background-color', a);
                        	};

                        	$(document).ready( function() {
                        		farbtastic_$id = $.farbtastic('#$id-colorPickerDiv', pickColor_$id);

                        		pickColor_$id( $('#$id').val() );

                        		$('.pickcolor').click( function(e) {
                        			$('#$id-colorPickerDiv').show();
                        			e.preventDefault();
                        		});

                        		$('#$id').keyup( function() {
                        			var a = $('#$id').val(),
                        				b = a;

                        			a = a.replace(/[^a-fA-F0-9]/, '');
                        			if ( '#' + a !== b )
                        				$('#$id').val(a);
                        			if ( a.length === 3 || a.length === 6 )
                        				pickColor_$id( '#' + a );
                        		});

                        		$(document).mousedown( function() {
                        			$('#$id-colorPickerDiv').hide();
                        		});
                        	});
                        })(jQuery);
                        </script>
                        ";
        	        break;
        	}
	
        }

        /**
         * Validates user input before we save it via the Options API. If error add_setting_error
         *
         * @since 0.1
         * @param array $input Contains all the values submited to the POST.
         * @return array $input Contains sanitized values.
         * @todo Figure out best way to validate values.
         */
        function validate_machine($input) {
            foreach ($this->options as $k) {
                switch($k['type']){
                    case 'setting':
                        break;
                    case 'section':
                        break;
                    default:
                        // Validate a pattern
                        if($pattern){
                    	    if(!preg_match( $pattern, $input[$k['id']])) {
                        		add_settings_error(
                        			$k['id'],
                        			'seedprod_error',
                        			$k['error_msg'],
                        			'error'
                        		);
                        		unset($input[$k['id']]);
                        	}		
                        }
                        // Sanitize 
                	    if($k['type'] == 'image'){
                	        $input[$k['id']] = esc_url_raw($input[$k['id']]);
                	    }
        	    }
            }
        	return $input;
        }

        /**
         * Dummy function to be called by all sections from the Settings API. Define a custom function in the config.
         *
         * @since 0.1
         * @return string Empty
         */
        function section_dummy_desc() {
        	echo '';
        }
        
        /**
         * Returns Font Families
         *
         * @since 0.1
         * @return string or array
         */
        function font_families($family=null) {
            $fonts = array();
            $fonts['_arial'] = 'Helvetica, Arial, sans-serif';
            $fonts['_arial_black'] = 'Arial Black, Arial Black, Gadget, sans-serif';
            $fonts['_georgia'] = 'Georgia,serif';
            $fonts['_helvetica_neue'] = '"Helvetica Neue", Helvetica, Arial, sans-serif';
            $fonts['_impact'] = 'Charcoal,Impact,sans-serif';
            $fonts['_lucida'] = 'Lucida Grande,Lucida Sans Unicode, sans-serif';
            $fonts['_palatino'] = 'Palatino,Palatino Linotype, Book Antiqua, serif';
            $fonts['_tahoma'] = 'Geneva,Tahoma,sans-serif';
            $fonts['_times'] = 'Times,Times New Roman, serif';
            $fonts['_trebuchet'] = 'Trebuchet MS, sans-serif';
            $fonts['_verdana'] = 'Verdana, Geneva, sans-serif';
            if($family){
                $font_family=$fonts[$family];
                if(empty($font_family)){
                    $font_family = '"'. urldecode($family) . '",sans-serif' ;
                }
            }else{
                $font_family=$fonts;  
            }
        	return $font_family;
        }
        
        /**
         * Get list of fonts from google and web safe fonts.
         *
         * @since 0.1
         * @return array 
         */
         function font_field_list($show_google_fonts = true){
             //$fonts = unserialize(get_transient('seedprod_fonts'));
             $fonts = 's:6452:"a:184:{s:7:"empty_0";s:13:"Select a Font";s:10:"optgroup_1";s:12:"System Fonts";s:6:"_arial";s:5:"Arial";s:12:"_arial_black";s:11:"Arial Black";s:8:"_georgia";s:7:"Georgia";s:15:"_helvetica_neue";s:14:"Helvetica Neue";s:7:"_impact";s:6:"Impact";s:7:"_lucida";s:13:"Lucida Grande";s:9:"_palatino";s:8:"Palatino";s:7:"_tahoma";s:6:"Tahoma";s:6:"_times";s:15:"Times New Roman";s:10:"_trebuchet";s:9:"Trebuchet";s:8:"_verdana";s:7:"Verdana";s:13:"optgroupend_1";s:0:"";s:10:"optgroup_2";s:12:"Google Fonts";s:8:"Aclonica";s:8:"Aclonica";s:5:"Allan";s:5:"Allan";s:7:"Allerta";s:7:"Allerta";s:15:"Allerta+Stencil";s:15:"Allerta Stencil";s:8:"Amaranth";s:8:"Amaranth";s:24:"Annie+Use+Your+Telescope";s:24:"Annie Use Your Telescope";s:13:"Anonymous+Pro";s:13:"Anonymous Pro";s:5:"Anton";s:5:"Anton";s:19:"Architects+Daughter";s:19:"Architects Daughter";s:5:"Arimo";s:5:"Arimo";s:8:"Artifika";s:8:"Artifika";s:4:"Arvo";s:4:"Arvo";s:5:"Asset";s:5:"Asset";s:7:"Astloch";s:7:"Astloch";s:7:"Bangers";s:7:"Bangers";s:7:"Bentham";s:7:"Bentham";s:5:"Bevan";s:5:"Bevan";s:11:"Bigshot+One";s:11:"Bigshot One";s:13:"Bowlby+One+SC";s:13:"Bowlby One SC";s:7:"Brawler";s:7:"Brawler";s:4:"Buda";s:4:"Buda";s:5:"Cabin";s:5:"Cabin";s:12:"Cabin+Sketch";s:12:"Cabin Sketch";s:14:"Calligraffitti";s:14:"Calligraffitti";s:6:"Candal";s:6:"Candal";s:9:"Cantarell";s:9:"Cantarell";s:5:"Cardo";s:5:"Cardo";s:10:"Carter+One";s:10:"Carter One";s:6:"Caudex";s:6:"Caudex";s:18:"Cedarville+Cursive";s:18:"Cedarville Cursive";s:17:"Cherry+Cream+Soda";s:17:"Cherry Cream Soda";s:5:"Chewy";s:5:"Chewy";s:4:"Coda";s:4:"Coda";s:11:"Coming+Soon";s:11:"Coming Soon";s:5:"Copse";s:5:"Copse";s:6:"Corben";s:6:"Corben";s:7:"Cousine";s:7:"Cousine";s:21:"Covered+By+Your+Grace";s:21:"Covered By Your Grace";s:12:"Crafty+Girls";s:12:"Crafty Girls";s:12:"Crimson+Text";s:12:"Crimson Text";s:7:"Crushed";s:7:"Crushed";s:6:"Cuprum";s:6:"Cuprum";s:6:"Damion";s:6:"Damion";s:14:"Dancing+Script";s:14:"Dancing Script";s:20:"Dawning+of+a+New+Day";s:20:"Dawning of a New Day";s:13:"Didact+Gothic";s:13:"Didact Gothic";s:10:"Droid+Sans";s:10:"Droid Sans";s:15:"Droid+Sans+Mono";s:15:"Droid Sans Mono";s:11:"Droid+Serif";s:11:"Droid Serif";s:11:"EB+Garamond";s:11:"EB Garamond";s:13:"Expletus+Sans";s:13:"Expletus Sans";s:16:"Fontdiner+Swanky";s:16:"Fontdiner Swanky";s:5:"Forum";s:5:"Forum";s:12:"Francois+One";s:12:"Francois One";s:3:"Geo";s:3:"Geo";s:10:"Goblin+One";s:10:"Goblin One";s:21:"Goudy+Bookletter+1911";s:21:"Goudy Bookletter 1911";s:12:"Gravitas+One";s:12:"Gravitas One";s:6:"Gruppo";s:6:"Gruppo";s:15:"Hammersmith+One";s:15:"Hammersmith One";s:15:"Holtwood+One+SC";s:15:"Holtwood One SC";s:14:"Homemade+Apple";s:14:"Homemade Apple";s:7:"IM+Fell";s:7:"IM Fell";s:11:"Inconsolata";s:11:"Inconsolata";s:12:"Indie+Flower";s:12:"Indie Flower";s:12:"Irish+Grover";s:12:"Irish Grover";s:12:"Josefin+Sans";s:12:"Josefin Sans";s:12:"Josefin+Slab";s:12:"Josefin Slab";s:6:"Judson";s:6:"Judson";s:4:"Jura";s:4:"Jura";s:17:"Just+Another+Hand";s:17:"Just Another Hand";s:23:"Just+Me+Again+Down+Here";s:23:"Just Me Again Down Here";s:7:"Kameron";s:7:"Kameron";s:5:"Kenia";s:5:"Kenia";s:6:"Kranky";s:6:"Kranky";s:5:"Kreon";s:5:"Kreon";s:6:"Kristi";s:6:"Kristi";s:15:"La+Belle+Aurore";s:15:"La Belle Aurore";s:4:"Lato";s:4:"Lato";s:13:"League+Script";s:13:"League Script";s:6:"Lekton";s:6:"Lekton";s:9:"Limelight";s:9:"Limelight";s:7:"Lobster";s:7:"Lobster";s:11:"Lobster+Two";s:11:"Lobster Two";s:4:"Lora";s:4:"Lora";s:21:"Love+Ya+Like+A+Sister";s:21:"Love Ya Like A Sister";s:17:"Loved+by+the+King";s:17:"Loved by the King";s:12:"Luckiest+Guy";s:12:"Luckiest Guy";s:13:"Maiden+Orange";s:13:"Maiden Orange";s:4:"Mako";s:4:"Mako";s:9:"Maven+Pro";s:9:"Maven Pro";s:6:"Meddon";s:6:"Meddon";s:13:"MedievalSharp";s:13:"MedievalSharp";s:6:"Megrim";s:6:"Megrim";s:12:"Merriweather";s:12:"Merriweather";s:11:"Metrophobic";s:11:"Metrophobic";s:8:"Michroma";s:8:"Michroma";s:9:"Miltonian";s:9:"Miltonian";s:7:"Molengo";s:7:"Molengo";s:8:"Monofett";s:8:"Monofett";s:22:"Mountains+of+Christmas";s:22:"Mountains of Christmas";s:4:"Muli";s:4:"Muli";s:6:"Neucha";s:6:"Neucha";s:6:"Neuton";s:6:"Neuton";s:10:"News+Cycle";s:10:"News Cycle";s:6:"Nobile";s:6:"Nobile";s:4:"Nova";s:4:"Nova";s:6:"Nunito";s:6:"Nunito";s:23:"OFL+Sorts+Mill+Goudy+TT";s:23:"OFL Sorts Mill Goudy TT";s:15:"Old+Standard+TT";s:15:"Old Standard TT";s:9:"Open+Sans";s:9:"Open Sans";s:8:"Orbitron";s:8:"Orbitron";s:6:"Oswald";s:6:"Oswald";s:16:"Over+the+Rainbow";s:16:"Over the Rainbow";s:7:"PT+Sans";s:7:"PT Sans";s:8:"PT+Serif";s:8:"PT Serif";s:8:"Pacifico";s:8:"Pacifico";s:12:"Patrick+Hand";s:12:"Patrick Hand";s:11:"Paytone+One";s:11:"Paytone One";s:16:"Permanent+Marker";s:16:"Permanent Marker";s:11:"Philosopher";s:11:"Philosopher";s:4:"Play";s:4:"Play";s:16:"Playfair+Display";s:16:"Playfair Display";s:7:"Podkova";s:7:"Podkova";s:7:"Puritan";s:7:"Puritan";s:12:"Quattrocento";s:12:"Quattrocento";s:17:"Quattrocento+Sans";s:17:"Quattrocento Sans";s:6:"Radley";s:6:"Radley";s:7:"Raleway";s:7:"Raleway";s:9:"Redressed";s:9:"Redressed";s:13:"Reenie+Beanie";s:13:"Reenie Beanie";s:9:"Rock+Salt";s:9:"Rock Salt";s:7:"Rokkitt";s:7:"Rokkitt";s:14:"Ruslan+Display";s:14:"Ruslan Display";s:10:"Schoolbell";s:10:"Schoolbell";s:18:"Shadows+Into+Light";s:18:"Shadows Into Light";s:6:"Shanti";s:6:"Shanti";s:10:"Sigmar+One";s:10:"Sigmar One";s:8:"Six+Caps";s:8:"Six Caps";s:7:"Slackey";s:7:"Slackey";s:6:"Smythe";s:6:"Smythe";s:7:"Sniglet";s:7:"Sniglet";s:13:"Special+Elite";s:13:"Special Elite";s:15:"Stardos+Stencil";s:15:"Stardos Stencil";s:19:"Sue+Ellen+Francisco";s:19:"Sue Ellen Francisco";s:9:"Sunshiney";s:9:"Sunshiney";s:18:"Swanky+and+Moo+Moo";s:18:"Swanky and Moo Moo";s:9:"Syncopate";s:9:"Syncopate";s:9:"Tangerine";s:9:"Tangerine";s:10:"Tenor+Sans";s:10:"Tenor Sans";s:20:"Terminal+Dosis+Light";s:20:"Terminal Dosis Light";s:18:"The+Girl+Next+Door";s:18:"The Girl Next Door";s:5:"Tinos";s:5:"Tinos";s:6:"Ubuntu";s:6:"Ubuntu";s:5:"Ultra";s:5:"Ultra";s:14:"UnifrakturCook";s:14:"UnifrakturCook";s:18:"UnifrakturMaguntia";s:18:"UnifrakturMaguntia";s:7:"Unkempt";s:7:"Unkempt";s:5:"VT323";s:5:"VT323";s:6:"Varela";s:6:"Varela";s:5:"Vibur";s:5:"Vibur";s:8:"Vollkorn";s:8:"Vollkorn";s:23:"Waiting+for+the+Sunrise";s:23:"Waiting for the Sunrise";s:8:"Wallpoet";s:8:"Wallpoet";s:15:"Walter+Turncoat";s:15:"Walter Turncoat";s:8:"Wire+One";s:8:"Wire One";s:17:"Yanone+Kaffeesatz";s:17:"Yanone Kaffeesatz";s:6:"Zeyada";s:6:"Zeyada";s:13:"optgroupend_2";s:0:"";}";';
             if($fonts === false){
                 if($show_google_fonts){
                     $query = urlencode('select * from html where url="http://www.google.com/webfonts" and xpath=\'//div[@class="preview"]/span\'');
                     $request = "http://query.yahooapis.com/v1/public/yql?q={$query}&format=json";
                     $reponse = wp_remote_get($request);
                     $result = json_decode($reponse['body']);
                     foreach($result->query->results->span as $v){
                        $google_fonts[urlencode($v)] = $v;
                     }
                     asort($google_fonts);
                     $pre2["optgroup_2"] = "Google Fonts";
                     $post2["optgroupend_2"] = "";
                 }
                 $post1["optgroupend_1"] = "";
                 $system_fonts['_arial'] = 'Arial';
                 $system_fonts['_arial_black'] = 'Arial Black';
                 $system_fonts['_georgia'] = 'Georgia';
                 $system_fonts['_helvetica_neue'] = 'Helvetica Neue';
                 $system_fonts['_impact'] = 'Impact';
                 $system_fonts['_lucida'] = 'Lucida Grande';
                 $system_fonts['_palatino'] = 'Palatino';
                 $system_fonts['_tahoma'] = 'Tahoma';
                 $system_fonts['_times'] = 'Times New Roman';
                 $system_fonts['_trebuchet'] = 'Trebuchet';
                 $system_fonts['_verdana'] = 'Verdana';
                 $pre0["empty_0"] = "Select a Font";
                 $pre1["optgroup_1"] = "System Fonts";
                 $pre2["optgroup_2"] = "Google Fonts";
                 $fonts =  $pre0 + $pre1 + $system_fonts+ $post1+ $pre2 + $google_fonts + $post2;
                 if(!empty($google_fonts)){
                     set_transient('seedprod_fonts',serialize( $fonts ),86400);
                }
             }
             return $fonts;
         }
         
         /**
          * SeedProd version of WP's do_settings_sections
          *
          * @since 0.1
          */
         function seedprod_do_settings_sections($page) {
             global $wp_settings_sections, $wp_settings_fields;

             if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) )
                 return;

             foreach ( (array) $wp_settings_sections[$page] as $section ) {
                 echo "<h3 class='hndle'>{$section['title']}</h3>\n";
                 echo '<div class="inside">';
                 call_user_func($section['callback'], $section);
                 if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]) )
                     continue;
                 echo '<table class="form-table">';
                 do_settings_fields($page, $section['id']);
                 echo '</table>';
                 echo '</div>';
             }
         }

    }
}
?>