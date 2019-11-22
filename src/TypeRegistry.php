<?php
/**
 * Registers BuddyPress types to the schema.
 *
 * @package \WPGraphQL\Extensions\BuddyPress
 * @since   0.0.1-alpha
 */

namespace WPGraphQL\Extensions\BuddyPress;

use WPGraphQL\AppContext;
use WPGraphQL\Extensions\BuddyPress\Data\Loader\GroupObjectLoader;

/**
 * Class TypeRegistry
 */
class TypeRegistry {

	/**
	 * Registers actions related to the type registry.
	 */
	public static function add_actions() {
		add_action( 'graphql_register_types', [ __CLASS__, 'graphql_register_types' ], 10 );
	}

	/**
	 * Registers filters related to the type registry.
	 */
	public static function add_filters() {
		add_filter( 'graphql_data_loaders', [ __CLASS__, 'graphql_register_autoloaders' ], 10, 2 );
	}

	/**
	 * Registers BuddyPress types, connection, and mutations to GraphQL schema.
	 */
	public static function graphql_register_types() {

		// Groups component.
		if ( bp_is_active( 'groups' ) ) {

			// Enum(s).
			\WPGraphQL\Extensions\BuddyPress\Type\Enum\GroupEnums::register();

			// Object(s).
			\WPGraphQL\Extensions\BuddyPress\Type\Object\GroupType::register();

			// Connections.
			\WPGraphQL\Extensions\BuddyPress\Connection\GroupConnection::register_connections();

			// Mutations.
			\WPGraphQL\Extensions\BuddyPress\Mutation\GroupCreate::register_mutation();
			\WPGraphQL\Extensions\BuddyPress\Mutation\GroupDelete::register_mutation();
			\WPGraphQL\Extensions\BuddyPress\Mutation\GroupUpdate::register_mutation();
		}
	}

	/**
	 * Registers custom autoloaders.
	 *
	 * @param array      $loaders Autoloaders.
	 * @param AppContext $context Context.
	 *
	 * @return array
	 */
	public static function graphql_register_autoloaders( array $loaders, AppContext $context ) {
		return array_merge(
			$loaders,
			[
				'group_object' => new GroupObjectLoader( $context ),
			]
		);
	}
}
