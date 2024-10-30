<?php

global $custom_feeds;

echo '<div class="wrap">';
echo '<h2>Custom Feeds</h2>';

if ( !empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'custom-feeds-add_feed' ) ) {
    if ( !empty( $_POST['feed']['slug'] ) ) {
        if ( !$custom_feeds->is_unique( $_POST['feed']['slug'] ) ) {
            echo '<div id="custom-feeds-feed_added" class="error custom-feeds-error"><p><strong>Feed slug</strong> <code>' . $_POST['feed']['slug'] . '</code> <strong>is not unique. Custom feed not added.</strong></p></div>';
        } else {
            $result = $custom_feeds->create_feed( $_POST['feed'] );

            if ( isset( $result ) && true === (boolean) $result ) {
                echo '<div id="custom-feeds-feed_added" class="updated custom-feeds-error"><p><code>' . $_POST['feed']['slug'] . '</code> <strong>feed has been added.</strong></p></div>';
            }

            if ( isset( $result ) && false === (boolean) $result ) {
                echo '<div id="custom-feeds-feed_added" class="error custom-feeds-error"><p><strong>An error occured and the feed was not added.</strong></p></div>';
            }
        }
    }
}

if ( !empty( $_POST['_wpnonce'] ) &&  wp_verify_nonce( $_POST['_wpnonce'], 'custom-feeds-delete_feed' ) ) {
    if ( !empty( $_POST['delete-feed'] ) ) {
        $result = $custom_feeds->delete_feed( $_POST['delete-feed'] );

        if ( isset( $result ) && true === (boolean) $result ) {
            echo '<div id="custom-feeds-feed_added" class="updated custom-feeds-error"><p><code>' . $_POST['delete-feed']. '</code> <strong>feed deleted.' . '</strong></p></div>';
        }

        if ( isset( $result ) && false === (boolean) $result ) {
            echo '<div id="custom-feeds-feed_added" class="error custom-feeds-error"><p><strong>Either an error occured or there is nothing to delete.</strong></p></div>';
        }
    }
}

echo '<h3> Add a new feed</h3>';

//Form to add feed
echo '<form id="custom-feeds-add-feed-form" name="add-feed" action "" method="post">';

wp_nonce_field( 'custom-feeds-add_feed' );

echo '<table class="wp-list-table widefat fixed posts">';
echo '  <thead>';
echo '      <tr>';
echo '          <th scope="row" id="custom-feed-slug" class="manage-column">';
echo '              <label for="feed-slug">Slug</label>';
echo '              <input type="text" id="feed-slug" name="feed[slug]" class="regular-text" size="10" value="" />';
echo '          </th>';
echo '          <th scope="row" id="custom-feed-description" class="manage-column">';
echo '              <label for="feed-description">Description</label>';
echo '              <input type="text" id="feed-description" name="feed[description]" class="regular-text" size="40" value="" />';
echo '          </th>';
echo '          <th scope="row" id="custom-feed-submit" class="manage-column">';

submit_button( 'Add feed', 'primary', 'submit', false );

echo '          </th>';
echo '      </tr>';
echo '  </thead>';
echo '</table>';

echo '</form>';

//Custom feed listings
$feeds = get_option( 'custom_feeds' );

if ( !empty( $feeds ) ) {
    echo '<script tye="application/javascript">';
    echo '  jQuery(document).ready(function() {';
    echo '      jQuery("input[name*=\'delete\']").click(function(){';
    echo '          jQuery("input#feeds-to-delete").val(jQuery(this).data("feed"));';
    echo '      });';
    echo '  });';
    echo '</script>';

    echo '<form id="custom-feeds-delete-feed-form" name="delete-feed" action "" method="post">';
    echo '<input type="hidden" id="feeds-to-delete" name="delete-feed" value="" />';

    wp_nonce_field( 'custom-feeds-delete_feed' );

    echo '<h3>Feeds</h3>';
    echo '<table class="wp-list-table widefat fixed posts">';
    echo '  <thead>';
    echo '      <th scope="col">Slug</th>';
    echo '      <th scope="col">Template</th>';
    echo '      <th scope="col">Description</th>';
    echo '      <th scope="col">Delete?</th>';
    echo '  </thead>';

    foreach( $feeds as $feed ) {
        echo '<tr>';
        echo '  <td><a href="' . get_option( 'home' ) . '/?feed=' . $feed['slug'] . '" target="_blank">' . $feed['slug'] . '</a></td>';
        echo '  <td>';

        if ( true === $feed['has_template'] && file_exists( get_stylesheet_directory() . '/feed-' . $feed['slug'] . '.php' ) ) {
            echo '<code>' . preg_replace( '/^(.*)(?=\/wp-content)/', '', get_stylesheet_directory() . '/feed-' . $feed['slug'] . '.php' ) . '</code>';
        } else {
            echo 'Default WordPress template';
        }

        echo '  </td>';
        echo '  <td><p>' . $feed['description'] . '</p></td>';
        echo '  <td>' . get_submit_button( 'Delete feed', 'delete', 'delete', false, array( 'id' => 'delete-' . $feed['slug'], 'data-feed' => $feed['slug'] ) ) . '</td>';

        echo '</tr>';
    }

    echo '</table>';
    echo '</form>';
} else {
    echo '<p>There are currently no custom feeds. Add a new feed using the form above.</p>';
}

echo '</div><!--.wrap-->';