<?php
/**
 * Admin Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_post_sqbc_analyze', array( $this, 'analyze_posts' ) );
		add_action( 'admin_post_sqbc_convert', array( $this, 'convert_posts' ) );
	}

	public function register_menu() {
		add_menu_page(
			'Smart Quote Block Converter',
			'Quote Converter',
			'manage_options',
			'sqbc-dashboard',
			array( $this, 'dashboard_page' ),
			'dashicons-format-quote',
			30
		);
	}

	public function dashboard_page() {
		$last_report = get_option( 'sqbc_last_report', array() );
		?>
		<div class="wrap">
			<h1>Smart Quote Block Converter</h1>

			<p>हे plugin H2 खालील quote/wishes/shayari paragraphs ला Gutenberg Quote Block मध्ये convert करेल.</p>

			<hr>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:10px;">
				<?php wp_nonce_field( 'sqbc_analyze_action', 'sqbc_nonce' ); ?>
				<input type="hidden" name="action" value="sqbc_analyze">
				<button type="submit" class="button button-secondary button-large">Analyze Posts</button>
			</form>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;">
				<?php wp_nonce_field( 'sqbc_convert_action', 'sqbc_nonce' ); ?>
				<input type="hidden" name="action" value="sqbc_convert">
				<button type="submit" class="button button-primary button-large" onclick="return confirm('Are you sure? Please take backup before converting.');">Convert All</button>
			</form>

			<hr>

			<?php if ( ! empty( $last_report ) ) : ?>
				<h2>Last Report</h2>
				<table class="widefat striped" style="max-width:700px;">
					<tbody>
						<tr>
							<th>Total Posts Checked</th>
							<td><?php echo esc_html( $last_report['total_posts'] ?? 0 ); ?></td>
						</tr>
						<tr>
							<th>Posts With Changes</th>
							<td><?php echo esc_html( $last_report['changed_posts'] ?? 0 ); ?></td>
						</tr>
						<tr>
							<th>Total Paragraphs Converted</th>
							<td><?php echo esc_html( $last_report['total_converted'] ?? 0 ); ?></td>
						</tr>
						<tr>
							<th>Mode</th>
							<td><?php echo esc_html( $last_report['mode'] ?? '' ); ?></td>
						</tr>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}

	public function analyze_posts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Permission denied.' );
		}

		check_admin_referer( 'sqbc_analyze_action', 'sqbc_nonce' );

		$report = $this->process_posts( true );
		$report['mode'] = 'Analyze Only';

		update_option( 'sqbc_last_report', $report );

		wp_safe_redirect( admin_url( 'admin.php?page=sqbc-dashboard' ) );
		exit;
	}

	public function convert_posts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Permission denied.' );
		}

		check_admin_referer( 'sqbc_convert_action', 'sqbc_nonce' );

		$report = $this->process_posts( false );
		$report['mode'] = 'Converted';

		update_option( 'sqbc_last_report', $report );

		wp_safe_redirect( admin_url( 'admin.php?page=sqbc-dashboard' ) );
		exit;
	}

	private function process_posts( $dry_run = true ) {
		$scanner   = new SQBC_Scanner();
		$converter = new SQBC_Converter();

		$post_ids = $scanner->get_posts();

		$total_posts     = count( $post_ids );
		$changed_posts   = 0;
		$total_converted = 0;

		foreach ( $post_ids as $post_id ) {
			$result = $converter->convert_post( $post_id, $dry_run );

			if ( ! empty( $result['converted'] ) ) {
				$changed_posts++;
				$total_converted += intval( $result['converted'] );
			}
		}

		return array(
			'total_posts'     => $total_posts,
			'changed_posts'   => $changed_posts,
			'total_converted' => $total_converted,
			'time'            => current_time( 'mysql' ),
		);
	}
}
