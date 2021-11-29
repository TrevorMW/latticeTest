<?php
/**
 * LearnDash REST API V2 Echo Controller.
 *
 * This Controller class is used simple echo back method, requests, and payload
 *
 * This class extends the WP_REST_Controller class.
 *
 * @since 3.3.0
 * @package LearnDash\REST\V2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ( ! class_exists( 'LD_REST_Echo_Controller_V2' ) ) && ( class_exists( 'WP_REST_Controller' ) ) ) {

	/**
	 * Class LearnDash REST API V2 Echo Controller.
	 *
	 * @since 3.3.0
	 * @uses WP_REST_Controller
	 */
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
	class LD_REST_Echo_Controller_V2 extends WP_REST_Controller {
		protected $version = 'v2';

		/**
		 * Public constructor for class
		 *
		 * @since 3.3.0
		 */
		public function __construct() {
			$this->namespace = trailingslashit( LEARNDASH_REST_API_NAMESPACE ) . $this->version;
			$this->rest_base = 'echo';
		}

		/**
		 * Registers the routes for the objects of the controller.
		 *
		 * @see register_rest_route()
		 *
		 * @since 3.3.0
		 */
		public function register_routes() {
			register_rest_route(
				$this->namespace,
				'/' . $this->rest_base,
				array(
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_repsonse' ),
						'permission_callback' => array( $this, 'get_repsonse_permissions_check' ),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'get_repsonse' ),
						'permission_callback' => array( $this, 'get_repsonse_permissions_check' ),
					),
					array(
						'methods'             => WP_REST_Server::DELETABLE,
						'callback'            => array( $this, 'get_repsonse' ),
						'permission_callback' => array( $this, 'get_repsonse_permissions_check' ),
					),
					'schema' => array( $this, 'get_item_schema' ),
				)
			);
		}

		/**
		 * Checks if a given request has access to read the theme.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has read access for the item, otherwise WP_Error object.
		 */
		public function get_repsonse_permissions_check( $request ) {
			return true;
		}

		/**
		 * Retrieves a collection of themes.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function get_repsonse( $request ) {
			$response_array                  = array();
			$response_array['method']        = $request->get_method();
			$response_array['route']         = $request->get_route();
			$response_array['authenticated'] = is_user_logged_in() ? 1 : 0;
			$response_array['query_params']  = $request->get_query_params();

			$request_body = $request->get_body();
			if ( ! empty( $request_body ) ) {
				$request_body                   = json_decode( $request_body, true );
				$response_array['content-type'] = $request->get_header( 'content-type' );
				$response_array['body']         = $request_body;
			} else {
				$response_array['body'] = '';
			}

			$response = rest_ensure_response( $response_array );

			$response->header( 'X-WP-Total', count( $response_array ) );
			$response->header( 'X-WP-TotalPages', count( $response_array ) );

			return $response;
		}

		/**
		 * Retrieves the theme's schema, conforming to JSON Schema.
		 *
		 * @since 3.3.0
		 *
		 * @return array Item schema data.
		 */
		public function get_item_schema() {
			$schema = array();

			return $this->add_additional_fields_schema( $schema );
		}
	}
}
