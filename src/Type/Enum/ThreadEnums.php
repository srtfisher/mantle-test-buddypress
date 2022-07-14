<?php
/**
 * Thread Enums.
 *
 * @package WPGraphQL\Extensions\BuddyPress\Type\Enum
 * @since 0.0.1-alpha
 */

namespace WPGraphQL\Extensions\BuddyPress\Type\Enum;

/**
 * ThreadEnums Class.
 */
class ThreadEnums {

	/**
	 * Registers enum types.
	 */
	public static function register(): void {

		register_graphql_enum_type(
			'ThreadIdTypeEnum',
			[
				'description' => __( 'The Type of Identifier used to fetch a single resource. Default is ID.', 'wp-graphql-buddypress' ),
				'values'      => [
					'ID'          => [
						'name'        => 'ID',
						'value'       => 'id',
						'description' => __( 'The globally unique ID', 'wp-graphql-buddypress' ),
					],
					'DATABASE_ID' => [
						'name'        => 'DATABASE_ID',
						'value'       => 'database_id',
						'description' => __( 'The Database ID for the node', 'wp-graphql-buddypress' ),
					],
				],
			]
		);

		register_graphql_enum_type(
			'ThreadTypeEnum',
			[
				'description' => __( 'Shorthand for certain filter thread type combinations.', 'wp-graphql-buddypress' ),
				'values'      => [
					'ALL'    => [
						'name'        => 'ALL',
						'description' => __( 'Used to filter all threads.', 'wp-graphql-buddypress' ),
						'value'       => 'all',
					],
					'READ'   => [
						'name'        => 'READ',
						'description' => __( 'Used to filter threads by the read status.', 'wp-graphql-buddypress' ),
						'value'       => 'read',
					],
					'UNREAD' => [
						'name'        => 'UNREAD',
						'description' => __( 'Used to filter threads by unread status.', 'wp-graphql-buddypress' ),
						'value'       => 'unread',
					],
				],
			]
		);

		register_graphql_enum_type(
			'ThreadBoxEnum',
			[
				'description' => __( 'Shorthand for certain filter thread box combinations.', 'wp-graphql-buddypress' ),
				'values'      => [
					'INBOX'   => [
						'name'        => 'INBOX',
						'description' => __( 'Used to get threads by inbox.', 'wp-graphql-buddypress' ),
						'value'       => 'inbox',
					],
					'SENTBOX' => [
						'name'        => 'SENTBOX',
						'description' => __( 'Used to get threads by sent box.', 'wp-graphql-buddypress' ),
						'value'       => 'sentbox',
					],
				],
			]
		);

		register_graphql_enum_type(
			'MessageTypeEnum',
			[
				'description' => __( 'Shorthand for certain filter message combinations.', 'wp-graphql-buddypress' ),
				'values'      => [
					'ALL'     => [
						'name'        => 'ALL',
						'description' => __( 'Used to get all messages (starred and unstarred).', 'wp-graphql-buddypress' ),
						'value'       => 'all',
					],
					'STARRED' => [
						'name'        => 'STARRED',
						'description' => __( 'Used to get starred messages.', 'wp-graphql-buddypress' ),
						'value'       => 'starred',
					],
				],
			]
		);
	}
}
