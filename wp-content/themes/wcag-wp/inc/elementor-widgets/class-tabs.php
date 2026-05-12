<?php
/**
 * Accessible tabs widget (WAI-ARIA APG manual activation tabs).
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tabs extends Base {

	public function get_name() {
		return 'wcag-tabs';
	}

	public function get_title() {
		return esc_html__( 'WCAG Tabs', 'wcag-wp' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => esc_html__( 'Tabs', 'wcag-wp' ) )
		);

		$this->add_control(
			'orientation',
			array(
				'label'   => esc_html__( 'Orientation', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'wcag-wp' ),
					'vertical'   => esc_html__( 'Vertical', 'wcag-wp' ),
				),
			)
		);

		$this->add_control(
			'aria_label',
			array(
				'label'       => esc_html__( 'Tablist accessible name (aria-label)', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Sections', 'wcag-wp' ),
				'description' => esc_html__( 'Required by WAI-ARIA so screen readers announce the purpose of the tablist.', 'wcag-wp' ),
			)
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Tab label', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab', 'wcag-wp' ),
			)
		);
		$repeater->add_control(
			'content',
			array(
				'label'   => esc_html__( 'Tab content', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Tab content', 'wcag-wp' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Tabs', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'title' => esc_html__( 'Tab 1', 'wcag-wp' ), 'content' => esc_html__( 'Content 1', 'wcag-wp' ) ),
					array( 'title' => esc_html__( 'Tab 2', 'wcag-wp' ), 'content' => esc_html__( 'Content 2', 'wcag-wp' ) ),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$id  = 'wcag-tabs-' . $this->get_id();
		$orient = ( 'vertical' === $s['orientation'] ) ? 'vertical' : 'horizontal';
		?>
		<div class="wcag-tabs wcag-tabs--<?php echo esc_attr( $orient ); ?>" id="<?php echo esc_attr( $id ); ?>">
			<div role="tablist"
				aria-label="<?php echo esc_attr( $s['aria_label'] ); ?>"
				aria-orientation="<?php echo esc_attr( $orient ); ?>"
				class="wcag-tabs__list">
				<?php foreach ( (array) $s['items'] as $i => $item ) :
					$tab_id   = $id . '-tab-' . $i;
					$panel_id = $id . '-panel-' . $i;
					$selected = 0 === $i ? 'true' : 'false';
					$tabindex = 0 === $i ? '0' : '-1';
					?>
					<button type="button"
						role="tab"
						id="<?php echo esc_attr( $tab_id ); ?>"
						aria-selected="<?php echo esc_attr( $selected ); ?>"
						aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						tabindex="<?php echo esc_attr( $tabindex ); ?>"
						class="wcag-tabs__tab">
						<?php echo esc_html( $item['title'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( (array) $s['items'] as $i => $item ) :
				$tab_id   = $id . '-tab-' . $i;
				$panel_id = $id . '-panel-' . $i;
				$hidden   = 0 === $i ? '' : 'hidden';
				?>
				<div role="tabpanel"
					id="<?php echo esc_attr( $panel_id ); ?>"
					aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
					tabindex="0"
					class="wcag-tabs__panel"
					<?php echo $hidden ? 'hidden' : ''; ?>>
					<?php echo wp_kses_post( $item['content'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
