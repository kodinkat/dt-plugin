<?php

namespace DT\Plugin\Middleware;

use DT\Plugin\CodeZone\Router\Middleware\Middleware;
use DT\Plugin\Illuminate\Http\Request;
use DT\Plugin\Symfony\Component\HttpFoundation\Response;

class Nonce implements Middleware {
	protected $nonce_name;

	public function __construct( $nonce_name ) {
		$this->nonce_name = $nonce_name;
	}

	public function handle( Request $request, Response $response, $next ) {
		$nonce = $request->header( 'X-WP-Nonce' ) ?? $request->get( '_wpnonce' );

		if ( empty( $nonce ) ) {
			$response->setContent( __( 'Could not verify request.', 'dt-plugin' ) );

			return $response->setStatusCode( 403 );
		}

		if ( ! wp_verify_nonce( $nonce, $this->nonce_name ) ) {
			return $response->setStatusCode( 403 );
		}

		return $next( $request, $response );
	}
}