<?php
/**
 * Registers BuddyPress member fields.
 *
 * @package WPGraphQL\Extensions\BuddyPress\Type\Object
 * @since 0.0.1-alpha
 */

namespace WPGraphQL\Extensions\BuddyPress\Type\Object;

use GraphQL\Error\UserError;
use WPGraphQL\Extensions\BuddyPress\Data\Factory;
use WPGraphQL\Model\User;
use BP_User_Query;

/**
 * MemberType Class.
 */
class MemberType {

	/**
	 * Name of the type.
	 *
	 * @var string Type name.
	 */
	public static $type_name = 'User';

	/**
	 * Register Member fields to the WPGraphQL schema.
	 */
	public static function register() {
		register_graphql_field(
			self::$type_name,
			'memberTypes',
			[
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Member types associated with the user.', 'wp-graphql-buddypress' ),
				'resolve'     => function ( User $source ) {
					$types = bp_get_member_type( $source->userId ?? 0, false );

					return $types ?? null;
				},
			]
		);

		register_graphql_field(
			self::$type_name,
			'mentionName',
			[
				'type'        => 'String',
				'description' => __( 'The name used for the user in @-mentions.', 'wp-graphql-buddypress' ),
				'resolve'     => function ( User $source ) {
					if ( ! bp_is_active( 'activity' ) ) {
						throw new UserError( __( 'The Activity component needs to be active to use this field.', 'wp-graphql-buddypress' ) );
					}

					$mention_name = bp_activity_get_user_mentionname( $source->userId ?? 0 );

					return $mention_name ?? null;
				},
			]
		);

		register_graphql_field(
			self::$type_name,
			'link',
			[
				'type'        => 'String',
				'description' => __( 'Profile URL of the member.', 'wp-graphql-buddypress' ),
				'resolve'     => function ( User $source ) {
					$link = bp_core_get_user_domain( $source->userId ?? 0 );

					return $link ?? null;
				},
			]
		);

		register_graphql_field(
			self::$type_name,
			'latestUpdate',
			[
				'type'        => 'String',
				'description' => __( 'The content of the latest activity posted by the member.', 'wp-graphql-buddypress' ),
				'args'        => [
					'format' => [
						'type'        => 'ContentFieldFormatEnum',
						'description' => __( 'Format of the field output', 'wp-graphql-buddypress' ),
					],
				],
				'resolve'     => function ( User $source ) {
					if ( ! bp_is_active( 'activity' ) ) {
						throw new UserError( __( 'The Activity component needs to be active to use this field.', 'wp-graphql-buddypress' ) );
					}

					// Get the member with BuddyPress extra data.
					$member_query = new BP_User_Query(
						[
							'user_ids'        => [ $source->userId ],
							'populate_extras' => true,
						]
					);

					$member = reset( $member_query->results );

					if ( empty( $member->latest_update ) ) {
						return null;
					}

					$activity_data = maybe_unserialize( $member->latest_update );

					if ( empty( $activity_data['content'] ) ) {
						return null;
					}

					if ( isset( $args['format'] ) && 'raw' === $args['format'] ) {
						return $activity_data['content'];
					}

					return apply_filters( 'bp_get_activity_content', $activity_data['content'] );
				},
			]
		);

		register_graphql_field(
			self::$type_name,
			'totalFriendCount',
			[
				'type'        => 'Int',
				'description' => __( 'Total number of friends for the member.', 'wp-graphql-buddypress' ),
				'resolve'     => function ( User $source ) {

					if ( ! bp_is_active( 'friends' ) ) {
						throw new UserError( __( 'The Friends component needs to be active to use this field.', 'wp-graphql-buddypress' ) );
					}

					// Get the member with BuddyPress extra data.
					$member_query = new BP_User_Query(
						[
							'user_ids'        => [ $source->userId ],
							'populate_extras' => true,
						]
					);

					$member = reset( $member_query->results );

					if ( empty( $member->total_friend_count ) ) {
						return null;
					}

					return absint( $member->total_friend_count );
				},
			]
		);

		register_graphql_field(
			self::$type_name,
			'attachmentAvatar',
			[
				'type'        => 'Attachment',
				'description' => __( 'Attachment Avatar of the member.', 'wp-graphql-buddypress' ),
				'resolve'     => function ( User $source ) {

					// Bail early, if disabled.
					if ( false === buddypress()->avatar->show_avatars ) {
						return null;
					}

					return Factory::resolve_attachment( $source->userId ?? 0 );
				},
			]
		);

		register_graphql_field(
			self::$type_name,
			'attachmentCover',
			[
				'type'        => 'Attachment',
				'description' => __( 'Attachment Cover of the member.', 'wp-graphql-buddypress' ),
				'resolve'     => function ( User $source ) {
					return Factory::resolve_attachment_cover( $source->userId ?? 0 );
				},
			]
		);
	}
}
