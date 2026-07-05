<?php

if (!defined('ABSPATH')) {
    exit;
}

class SQBC_Admin {

    public function __construct() {

        add_action('admin_menu', array($this, 'menu'));

    }

    public function menu() {

        add_menu_page(

            'Smart Quote Converter',

            'Quote Converter',

            'manage_options',

            'sqbc',

            array($this, 'dashboard'),

            'dashicons-format-quote',

            30

        );

    }

    public function dashboard() {

        ?>

        <div class="wrap">

            <h1>Smart Quote Block Converter</h1>

            <hr>

            <h2>Plugin Installed Successfully ✅</h2>

            <p>Next step we will build Scanner Engine.</p>

        </div>

        <?php

    }

}
