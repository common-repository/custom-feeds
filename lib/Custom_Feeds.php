<?php

class Custom_Feeds {
    public static $instance;
    public $feeds = array();
    public $current_feed;

    public function __construct() {
        self::$instance = $this;

        $this->feeds = get_option( 'custom_feeds' );
        
        add_action( 'init', array( &$this, 'load_feeds' ) );
    }

    public function create_feed( $feed ) {
        if ( empty( $feed ) ) {
            return false;
        }

        $feed['slug']         = sanitize_title( $feed['slug'] );
        $feed['description']  = sanitize_text_field( $feed['description'] );
        $feed['has_template'] = $this->has_template( $feed );
        $feed['created']      = current_time( 'mysql' );
        $this->feeds[]        = $feed;
        $result               = update_option( 'custom_feeds', $this->feeds );

        $this->flush_rewrites();

        return $result;
    }

    public function delete_feed( $feed ) {
        if ( !$feed ) {
            return false;
        }

        foreach( $this->feeds as $key => $values ) {
            if ( $feed == $values['slug'] ) {
                unset( $this->feeds[$key] );

                $deleted = true;

                break;
            }
        }

        if ( true === $deleted ) {
            update_option( 'custom_feeds', $this->feeds );
            
            return true;
        } else {
            return false;
        }
    }

    public function load_feeds() {
        if ( !empty( $this->feeds ) ) {
            foreach( $this->feeds as $key => $values ) {
                $this->feeds[$key]['has_template'] = $this->has_template( $values );
            }

            update_option( 'custom_feeds', $this->feeds );

            foreach( $this->feeds as $feed ) {
                $this->current_feed = $feed;

                add_feed( $feed['slug'], array( &$this, 'load_feed_template' ) );
            }
        }
    }

    public function load_feed_template() {
        if ( $this->current_feed['has_template'] ) {
            load_template( get_stylesheet_directory() . '/feed-' . $this->current_feed['slug'] . '.php' );
        } else {
            load_template( ABSPATH . WPINC . '/feed-rss2.php' );
        }
    }

    public function is_unique( $feed ) {
        if ( !empty( $this->feeds ) ) {
            $feed      = sanitize_title( $feed );
            $is_unique = null;

            foreach( $this->feeds as $ffeed ) {
                if ( $feed == $ffeed['slug'] ) {
                    $is_unique = false;
                    break;
                }
            }

            if ( !is_null( $is_unique ) && false === (boolean) $is_unique ) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function has_template( $feed ) {
        if ( file_exists( get_stylesheet_directory() . '/feed-' . $feed['slug'] . '.php' ) ) {
            return true;
        } else {
            return false;
        }
    }

    public function flush_rewrites() {
        global $wp_rewrite;

        $wp_rewrite->flush_rules();
    }
}