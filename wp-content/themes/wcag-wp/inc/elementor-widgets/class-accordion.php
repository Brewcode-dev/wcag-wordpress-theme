<?php
/**
 * Accessible accordion widget.
 *
 * Pattern: WAI-ARIA APG accordion.
 *  - <h{level}><button aria-expanded aria-controls></button></h{level}>
 *  - region panel labelled-by the trigger.
 *  - Keyboard: Up/Down arrows, Home/End, Enter/Space.
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Accordion extends Base {

	public function get_name() {
		return 'wcag-accordion';
	}

	public function get_title() {
		return esc_html__( 'WCAG Accordion', 'wcag-wp' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => esc_html__( 'Items', 'wcag-wp' ) )
		);

		$this->add_control(
			'heading_level',
			array(
				'label'   => esc_html__( 'Heading level of triggers', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
				),
				'description' => esc_html__( 'Each accordion trigger is wrapped in a heading so the structure exposes a proper outline.', 'wcag-wp' ),
			)
		);

		$this->add_control(
			'multiselect',
			array(
				'label'   => esc_html__( 'Allow multiple open at once', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Trigger title', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Item title', 'wcag-wp' ),
				'dynamic' => array( 'active' => true ),
			)
		);
		$repeater->add_control(
			'content',
			array(
				'label'   => esc_html__( 'Panel content', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Panel content goes here.', 'wcag-wp' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Items', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title'   => esc_html__( 'Question 1', 'wcag-wp' ),
						'content' => esc_html__( 'Answer 1', 'wcag-wp' ),
					),
					array(
						'title'   => esc_html__( 'Question 2', 'wcag-wp' ),
						'content' => esc_html__( 'Answer 2', 'wcag-wp' ),
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$id    = 'wcag-acc-' . $this->get_id();
		$tag   = in_array( $s['heading_level'], array( 'h2', 'h3', 'h4', 'h5' ), true ) ? $s['heading_level'] : 'h3';
		$multi = 'yes' === $s['multiselect'] ? 'true' : 'false';
		?>
		<div class="wcag-accordion" id="<?php echo esc_attr( $id ); ?>" data-multiselect="<?php echo esc_attr( $multi ); ?>">
			<?php foreach ( (array) $s['items'] as $i => $item ) :
				$btn_id   = $id . '-trigger-' . $i;
				$panel_id = $id . '-panel-' . $i;
				?>
				<div class="wcag-accordion__item">
					<<?php echo esc_attr( $tag ); ?> class="wcag-accordion__heading">
						<button type="button"
							class="wcag-accordion__trigger"
							id="<?php echo esc_attr( $btn_id ); ?>"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>">
							<span class="wcag-accordion__title"><?php echo esc_html( $item['title'] ); ?></span>
							<svg class="wcag-accordion__icon" aria-hidden="true" focusable="false" viewBox="0 0 20 20">
								<path fill="currentColor" d="M5 7l5 6 5-6z"/>
							</svg>
						</button>
					</<?php echo esc_attr( $tag ); ?>>
					<div class="wcag-accordion__panel"
						id="<?php echo esc_attr( $panel_id ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $btn_id ); ?>"
						hidden>
						<div class="wcag-accordion__content">
							<?php echo wp_kses_post( $item['content'] ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
