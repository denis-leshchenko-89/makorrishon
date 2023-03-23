<?php
/**
 * @author : Jegtheme
 */

namespace JNews;

use JNews\Template;
use JNews\Ajax\AccountHandler;
use JNews\Ajax\LiveSearch;
use JNews\Ajax\FirstLoadAction;
use JNews\Menu\Menu;
use JNews\Module\ModuleManager;
use JNews\Sidefeed\Sidefeed;
use JNews\Dashboard\SystemDashboard;

/**
 * Class JNews Frontend Ajax
 */
Class FrontendAjax {
	/**
	 * @var FrontendAjax
	 */
	private static $instance;

	private $endpoint = 'ajax-request';

	/**
	 * @return FrontendAjax
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * FrontendAjax constructor.
	 */
	private function __construct() {
		add_action( 'wp_head', array( $this, 'frontend_ajax_script' ), 1 );

		add_action( 'wp', array( $this, 'ajax_parse_request' ) );
		add_filter( 'query_vars', array( $this, 'ajax_query_vars' ) );
	}

	public function ajax_query_vars( $vars ) {
		$vars[] = $this->endpoint;
		$vars[] = 'action';

		return $vars;
	}

	public function is_doing_ajax() {
		return true;
	}

	public function ajax_parse_request( $wp ) {
		if ( array_key_exists( $this->endpoint, $wp->query_vars ) ) {
			// need to flag this request is ajax request
			add_filter( 'wp_doing_ajax', array( $this, 'is_doing_ajax' ) );
            // Nadav edition
            $action = $wp->query_vars['action'];
            if(preg_match('/isrh_form_*/', $action)) {
                $ishr_form = $action;
                $action = 'isrh_form';
            }

			switch ( $action ) {
				case 'jnews_first_load_action' :
					$fragment = new FirstLoadAction();
					$fragment->build_response( $_REQUEST['load_action'] );
					break;
				case 'jnews_newsfeed_load' :
					$sidefeed = new Sidefeed();
					$sidefeed->build_response();
					break;
				case 'jnews_ajax_live_search' :
					$search = new LiveSearch();
					$search->build_response();
					break;
				case 'jnews_mega_category_1' :
					$mega_menu = Menu::getInstance();
					$mega_menu->mega_menu_category_1_article();
					break;
				case 'jnews_mega_category_2' :
					$menu_menu = Menu::getInstance();
					$menu_menu->mega_menu_category_2_article();
					break;
				case 'jnews_build_mega_category_1' :
					$mega_menu = Menu::getInstance();
					$mega_menu->build_megamenu_category_1_article();
					break;
				case 'jnews_build_mega_category_2' :
					$mega_menu = Menu::getInstance();
					$mega_menu->build_megamenu_category_2_article();
					break;
				case 'jnews_refresh_nonce' :
					$this->refresh_nonce();
					break;
				case 'jnews_system' :
					wp_redirect( home_url() );
    				exit;
				// 	$template = new Template( JNEWS_THEME_DIR . 'class/Dashboard/template/' );
				// 	$system   = new SystemDashboard( $template );
				// 	$system->backend_status();
					break;
				case 'login_handler':
				case 'register_handler':
				case 'forget_password_handler':
					$account = AccountHandler::getInstance();
					$account->$action();
					break;
				case 'jnews_ajax_comment':
					// ajax comment
					query_posts( array( 'p' => $_REQUEST['post_id'], 'withcomments' => 1, 'feed' => 1 ) );

					while ( have_posts() ) : the_post();
						global $post;
						setup_postdata( $post );
						get_template_part( 'fragment/comments' );
					endwhile;

					wp_reset_query();
					break;
				case 'jnews_ajax_cart_detail':
					if ( function_exists( 'WC' ) ) {
						wp_send_json( jnews_return_translation( 'Cart', 'jnews', 'cart' ) . ' / ' . WC()->cart->get_cart_total() );
					}
                // Nadav edition
                case 'isrh_form':
                    $data = $_REQUEST['data'];
                    $inputs = array();
                    foreach ($data as $d) {
                        // to ignore the recaptcha field

                        if($d['name'] === 'g-recaptcha-response') {
                            continue;
                        }
                        $inputs[$d['name']] = $d['value'];
                    }
                    $this->call_ih_api('AddFormData', $ishr_form, $inputs);

					break;
			}

			// Module Ajax
			$module_prefix = ModuleManager::$module_ajax_prefix;
			if ( 0 === strpos( $action, $module_prefix ) ) {
				$module_name = str_replace( $module_prefix, '', $action );
				ModuleManager::getInstance()->module_ajax( $module_name );
			}

			do_action( 'jnews_ajax_' . $action );

			exit;
		}
	}
    /**
     * Make an API call to ih forms drupal
     * @param $method
     * @param $formType
     * @param $records
     */
    public function call_ih_api($method, $formType, $records) {
        $body = array(
            'token'             => $this->create_new_ih_token(),
            'field_form_type'   => $formType,
            'records'           => array(0 => $records)
        );
        $jsonContent = json_encode($body);
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $jsonContent
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents(IH_API_URL . $method . '.php', false, $context);
        wp_send_json($response);
    }

    /**
     * Create a token for ih forms request (the token time should be the same as the form server)
     * @return string
     */
    public function create_new_ih_token() {
        return sha1('web_auth' . date('YmdHi', time() + (3600 * 3)));
//        return sha1('web_auth' . date('YmdHi', time() + 3600));
    }
	public function ajax_url() {
		return add_query_arg( array( $this->endpoint => 'jnews' ), esc_url( home_url( '/', 'relative' ) ) );
	}

	public function refresh_nonce() {
		if ( ! empty( $_POST['refresh_action_nonce'] ) ) {
			wp_send_json(
				[
					'jnews_nonce' => wp_create_nonce( $_POST['refresh_action_nonce'] ),
				]
			);
		}
	}

	public function frontend_ajax_script() {
		if ( ! is_admin() ) {
			?>
			<script type="text/javascript">
              var jnews_ajax_url = '<?php echo esc_url( $this->ajax_url() ); ?>'
			</script>
			<?php
		}
	}
}