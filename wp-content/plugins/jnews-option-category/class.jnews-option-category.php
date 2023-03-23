<?php
/**
 * @author Jegtheme
 */

use JNews\Archive\Builder\OptionAbstract;

class OptionCategory extends OptionAbstract {

	protected $prefix = 'jnews_category_';

	protected function setup_hook() {
		add_action( 'edit_category_form', array( $this, 'render_options' ) );
		add_action( 'edit_category', array( $this, 'save_category' ) );
		add_action( 'pre_get_posts', array( $this, 'category_custom_get_posts' ) );
		add_action( 'jeg_after_inline_dynamic_css', array( $this, 'override_color' ) );

		add_filter( 'category_template', array( $this, 'get_category_template' ) );

		$this->override_global_category();
		$this->override_category_builder();
	}

	public function category_custom_get_posts( $query ) {
		if ( is_category() && $query->is_main_query() ) {
			$term = get_queried_object_id();

			if ( $term && $this->is_overwritten( $term ) ) {
				if ( $this->get_value( 'page_layout', $term, false ) === 'custom-template' ) {
					$query->query_vars['posts_per_page'] = (int) $this->get_value( 'number_post', $term, false );
				}
			}
		}
	}

	public function get_category_template( $template ) {
		if ( is_category() ) {
			$term = get_queried_object_id();

			if ( $term && $this->is_overwritten( $term ) ) {
				$layout      = $this->get_value( 'page_layout', $term, false );
				$template_id = $this->get_value( 'category_template', $term, false );

				if ( $layout === 'custom-template' && $template_id ) {
					$template = JNEWS_THEME_DIR . '/fragment/archive/category.php';
				}
			}
		}

		return $template;
	}

	protected function override_category_builder() {
		$self = $this;
		$keys = array(
			'page_layout'       => 'page_layout',
			'category_template' => 'custom_template_id',
			'number_post'       => 'custom_template_number_post'
		);

		foreach ( $keys as $key => $label ) {
			add_filter( 'theme_mod_' . $this->prefix . $label, function ( $value ) use ( $self, $key ) {

				if ( is_category() ) {
					$term = get_queried_object_id();

					if ( $term && $self->is_overwritten( $term ) ) {
						$new_option = get_option( $self->prefix . $key );

						if ( isset( $new_option[ $term ] ) ) {
							$value = $new_option[ $term ];
						}
					}
				}

				return $value;
			} );
		}
	}

	protected function override_global_category() {
		$self  = $this;
		$items = array(
			'page_layout'              => 'page_layout',
			'sidebar'                  => 'sidebar',
			'second_sidebar'           => 'second_sidebar',
			'header_style'             => 'header',
			'header_scheme'            => 'header_style',
			'title_bg_color'           => 'header_bg_color',
			'title_bg_image'           => 'header_bg_image',
			'show_hero'                => 'hero_show',
			'hero_layout'              => 'hero',
			'hero_style'               => 'hero_style',
			'hero_margin'              => 'hero_margin',
			'hero_date'                => 'hero_date',
			'hero_date_custom'         => 'hero_date_custom',
			'content_layout'           => 'content',
			'content_boxed'            => 'boxed',
			'content_boxed_shadow'     => 'boxed_shadow',
			'content_box_shadow'       => 'box_shadow',
			'content_excerpt'          => 'content_excerpt',
			'content_date'             => 'content_date',
			'content_date_custom'      => 'content_date_custom',
			'content_pagination'       => 'content_pagination',
			'content_pagination_limit' => 'content_pagination_limit',
			'content_pagination_align' => 'content_pagination_align',
			'content_pagination_text'  => 'content_pagination_show_navtext',
			'content_pagination_page'  => 'content_pagination_show_pageinfo',
		);

		foreach ( $items as $key => $label ) {
			add_filter( $this->prefix . $label, function ( $value ) use ( $self, $key ) {

				if ( is_category() ) {
					$term = get_queried_object_id();

					if ( $term && $self->is_overwritten( $term ) ) {
						$new_option = get_option( $self->prefix . $key );

						if ( isset( $new_option[ $term ] ) ) {
							$value = $new_option[ $term ];
						}
					}
				}

				return $value;
			} );
		}
	}

	public function is_overwritten( $term_id ) {
		$option = get_option( $this->prefix . 'category_override', array() );

		if ( isset( $option[ $term_id ] ) ) {
			return $option[ $term_id ];
		}

		return false;
	}

	public function override_color() {
		$style   = '';
		$options = get_option( $this->prefix . 'category_override_color', array() );

		foreach ( $options as $key => $option ) {
			if ( $option ) {
				$category = get_category( $key );

				if ( isset( $category->slug ) && $category->slug ) {
					$bg_color   = $this->get_value( 'category_bg_color', $key, false );
					$text_color = $this->get_value( 'category_text_color', $key, false );

					if ( $bg_color ) {
						$style .= ".jeg_heroblock .jeg_post_category a.category-{$category->slug},.jeg_thumb .jeg_post_category a.category-{$category->slug},.jeg_pl_lg_box .jeg_post_category a.category-{$category->slug},.jeg_pl_md_box .jeg_post_category a.category-{$category->slug},.jeg_postblock_carousel_2 .jeg_post_category a.category-{$category->slug},.jeg_slide_caption .jeg_post_category a.category-{$category->slug} { background-color:{$bg_color}; }";

						$style .= ".jeg_heroblock .jeg_post_category a.category-{$category->slug},.jeg_thumb .jeg_post_category a.category-{$category->slug},.jeg_pl_lg_box .jeg_post_category a.category-{$category->slug},.jeg_pl_md_box .jeg_post_category a.category-{$category->slug},.jeg_postblock_carousel_2 .jeg_post_category a.category-{$category->slug},.jeg_slide_caption .jeg_post_category a.category-{$category->slug} { border-color:{$bg_color}; }";
					}

					if ( $text_color ) {
						$style .= ".jeg_heroblock .jeg_post_category a.category-{$category->slug},.jeg_thumb .jeg_post_category a.category-{$category->slug},.jeg_pl_lg_box .jeg_post_category a.category-{$category->slug},.jeg_pl_md_box .jeg_post_category a.category-{$category->slug},.jeg_postblock_carousel_2 .jeg_post_category a.category-{$category->slug},.jeg_slide_caption .jeg_post_category a.category-{$category->slug} { color:{$text_color}; }";
					}

				}
			}
		}

		if ( $style ) {
			wp_add_inline_style( 'jeg-dynamic-style', $style );
		}
	}

	protected function get_id( $tag ) {
		if ( ! empty( $tag->term_id ) ) {
			return $tag->term_id;
		} else {
			return null;
		}
	}

	public function prepare_segments() {
		$segments = array();

		$segments[] = array(
			'id'   => 'override-category-setting',
			'name' => esc_html__( 'Category Setting', 'jnews-option-category' ),
		);

		return $segments;
	}

	public function save_category() {
		if ( isset( $_POST['taxonomy'] ) && $_POST['taxonomy'] === 'category' ) {
			$options = $this->get_options();
			$this->do_save( $options, $_POST['tag_ID'] );
		}
	}

	protected function get_options() {
		$options     = array();
		$all_sidebar = apply_filters( 'jnews_get_sidebar_widget', null );
		$content_layout = apply_filters('jnews_get_content_layout_option', array(
			'3'  => JNEWS_THEME_URL . '/assets/img/admin/content-3.png',
			'4'  => JNEWS_THEME_URL . '/assets/img/admin/content-4.png',
			'5'  => JNEWS_THEME_URL . '/assets/img/admin/content-5.png',
			'6'  => JNEWS_THEME_URL . '/assets/img/admin/content-6.png',
			'7'  => JNEWS_THEME_URL . '/assets/img/admin/content-7.png',
			'9'  => JNEWS_THEME_URL . '/assets/img/admin/content-9.png',
			'10' => JNEWS_THEME_URL . '/assets/img/admin/content-10.png',
			'11' => JNEWS_THEME_URL . '/assets/img/admin/content-11.png',
			'12' => JNEWS_THEME_URL . '/assets/img/admin/content-12.png',
			'14' => JNEWS_THEME_URL . '/assets/img/admin/content-14.png',
			'15' => JNEWS_THEME_URL . '/assets/img/admin/content-15.png',
			'18' => JNEWS_THEME_URL . '/assets/img/admin/content-18.png',
			'22' => JNEWS_THEME_URL . '/assets/img/admin/content-22.png',
			'23' => JNEWS_THEME_URL . '/assets/img/admin/content-23.png',
			'25' => JNEWS_THEME_URL . '/assets/img/admin/content-25.png',
			'26' => JNEWS_THEME_URL . '/assets/img/admin/content-26.png',
			'27' => JNEWS_THEME_URL . '/assets/img/admin/content-27.png',
			'32' => JNEWS_THEME_URL . '/assets/img/admin/content-32.png',
			'33' => JNEWS_THEME_URL . '/assets/img/admin/content-33.png',
			'34' => JNEWS_THEME_URL . '/assets/img/admin/content-34.png',
			'35' => JNEWS_THEME_URL . '/assets/img/admin/content-35.png',
			'36' => JNEWS_THEME_URL . '/assets/img/admin/content-36.png',
			'37' => JNEWS_THEME_URL . '/assets/img/admin/content-37.png',
			'38' => JNEWS_THEME_URL . '/assets/img/admin/content-38.png',
			'39' => JNEWS_THEME_URL . '/assets/img/admin/content-39.png'
		));

		$category_override = array(
			'field'    => 'category_override',
			'operator' => '==',
			'value'    => true
		);

		$custom_template = array(
			'field'    => 'page_layout',
			'operator' => '!=',
			'value'    => 'custom-template'
		);

		$category_override_color = array(
			'field'    => 'category_override_color',
			'operator' => '==',
			'value'    => true
		);

		/**
		 * Override category color
		 */
		$options['category_override_color'] = array(
			'segment' => 'override-category-setting',
			'title'   => esc_html__( 'Override Category Color', 'jnews-option-category' ),
			'desc'    => esc_html__( 'Override category general color setting.', 'jnews-option-category' ),
			'type'    => 'checkbox',
			'default' => false
		);

		$options['category_bg_color'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Background Color', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Main color for this category.', 'jnews-option-category' ),
			'default'    => '',
			'type'       => 'color',
			'dependency' => array(
				$category_override_color
			)
		);

		$options['category_text_color'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Text Color', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose text color for this category.', 'jnews-option-category' ),
			'default'    => '',
			'type'       => 'color',
			'dependency' => array(
				$category_override_color
			)
		);

		/**
		 * Override category setting
		 */
		$options['category_override'] = array(
			'segment' => 'override-category-setting',
			'title'   => esc_html__( 'Override Category Setting', 'jnews-option-category' ),
			'desc'    => esc_html__( 'Override category general setting.', 'jnews-option-category' ),
			'type'    => 'checkbox',
			'default' => false
		);

		$options['page_layout'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Page Layout', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your page layout.', 'jnews-option-category' ),
			'default'    => 'right-sidebar',
			'type'       => 'radioimage',
			'options'    => array(
				'right-sidebar'        => JNEWS_THEME_URL . '/assets/img/admin/single-post-right-sidebar.png',
				'left-sidebar'         => JNEWS_THEME_URL . '/assets/img/admin/single-post-left-sidebar.png',
				'right-sidebar-narrow' => JNEWS_THEME_URL . '/assets/img/admin/single-post-wide-right-sidebar.png',
				'left-sidebar-narrow'  => JNEWS_THEME_URL . '/assets/img/admin/single-post-wide-left-sidebar.png',
				'double-sidebar'       => JNEWS_THEME_URL . '/assets/img/admin/single-post-double-sidebar.png',
				'double-right-sidebar' => JNEWS_THEME_URL . '/assets/img/admin/single-post-double-right.png',
				'no-sidebar'           => JNEWS_THEME_URL . '/assets/img/admin/single-post-no-sidebar.png',
				'custom-template'      => JNEWS_THEME_URL . '/assets/img/admin/single-post-custom.png',
			),
			'dependency' => array(
				$category_override
			)
		);

		$options['category_template'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Template', 'jnews' ),
			'desc'       => esc_html__( 'Choose archive template that you want to use for this category.', 'jnews' ),
			'type'       => 'select',
			'options'    => jnews_get_all_custom_archive_template(),
			'dependency' => array(
				$category_override,
				array(
					'field'    => 'page_layout',
					'operator' => '==',
					'value'    => 'custom-template'
				)
			)
		);

		$options['number_post'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Number of Post', 'jnews' ),
			'desc'       => esc_html__( 'Set the number of post per page on category page.', 'jnews' ),
			'type'       => 'text',
			'default'    => '10',
			'dependency' => array(
				$category_override,
				array(
					'field'    => 'page_layout',
					'operator' => '==',
					'value'    => 'custom-template'
				)
			)
		);

		$options['sidebar'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Sidebar', 'jnews-option-category' ),
			'desc'       => wp_kses( __( "Choose your category sidebar. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.", 'jnews-option-category' ), wp_kses_allowed_html() ),
			'type'       => 'select',
			'default'    => 'default-sidebar',
			'options'    => $all_sidebar,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'page_layout',
					'operator' => '!=',
					'value'    => 'no-sidebar'
				)
			)
		);

		$options['second_sidebar'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Second Category Sidebar', 'jnews-option-category' ),
			'desc'       => wp_kses( __( "Choose your second sidebar for category page. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.", 'jnews-option-category' ), wp_kses_allowed_html() ),
			'type'       => 'select',
			'default'    => 'default-sidebar',
			'options'    => $all_sidebar,
			'dependency' => array(
				$category_override,
				array(
					'field'    => 'page_layout',
					'operator' => 'in',
					'value'    => array( 'double-sidebar', 'double-right-sidebar' )
				)
			)
		);

		$options['second_sidebar'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Sticky Sidebar', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Enable sticky sidebar on this category page.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => true,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'page_layout',
					'operator' => '!=',
					'value'    => 'no-sidebar'
				)
			)
		);

		$options['header_style'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Header Style', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Category header: title and description type.', 'jnews-option-category' ),
			'default'    => '1',
			'type'       => 'radioimage',
			'options'    => array(
				'1' => JNEWS_THEME_URL . '/assets/img/admin/header-style-1.png',
				'2' => JNEWS_THEME_URL . '/assets/img/admin/header-style-2.png',
				'3' => JNEWS_THEME_URL . '/assets/img/admin/header-style-3.png',
				'4' => JNEWS_THEME_URL . '/assets/img/admin/header-style-4.png'
			),
			'dependency' => array(
				$category_override,
				$custom_template
			)
		);

		$options['header_scheme'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Color Scheme', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose color for your category title background color.', 'jnews-option-category' ),
			'default'    => 'dark',
			'type'       => 'select',
			'options'    => array(
				'dark'   => esc_html__( 'Dark Style', 'jnews-option-category' ),
				'normal' => esc_html__( 'Normal Style (Light)', 'jnews-option-category' )
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'header_style',
					'operator' => 'in',
					'value'    => array( '3', '4' )
				)
			)
		);

		$options['title_bg_color'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Title Background Color', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose color for your category title background color.', 'jnews-option-category' ),
			'default'    => '#f5f5f5',
			'type'       => 'color',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'header_style',
					'operator' => 'in',
					'value'    => array( '3', '4' )
				)
			)
		);

		$options['title_bg_image'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Title Background Image', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose or upload image for your category background.', 'jnews-option-category' ),
			'default'    => '',
			'type'       => 'image',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'header_style',
					'operator' => 'in',
					'value'    => array( '3', '4' )
				)
			)
		);

		/**
		 * Override category hero
		 */
		$options['show_hero']   = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Show Category Hero Block', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Disable this option to hide category hero block.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
			)
		);
		$options['hero_layout'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Hero Header', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your category header (hero).', 'jnews-option-category' ),
			'default'    => '1',
			'type'       => 'radioimage',
			'options'    => array(
				'1'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-1.png',
				'2'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-2.png',
				'3'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-3.png',
				'4'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-4.png',
				'5'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-5.png',
				'6'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-6.png',
				'7'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-7.png',
				'8'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-8.png',
				'9'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-9.png',
				'10'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-10.png',
				'11'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-11.png',
				'12'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-12.png',
				'13'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-13.png',
				'skew' => JNEWS_THEME_URL . '/assets/img/admin/hero-type-skew.png',
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true
				)
			)
		);
		$options['hero_style']  = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Header Style', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your category header (hero) style.', 'jnews-option-category' ),
			'default'    => 'jeg_hero_style_1',
			'type'       => 'radioimage',
			'options'    => array(
				'jeg_hero_style_1' => JNEWS_THEME_URL . '/assets/img/admin/hero-1.png',
				'jeg_hero_style_2' => JNEWS_THEME_URL . '/assets/img/admin/hero-2.png',
				'jeg_hero_style_3' => JNEWS_THEME_URL . '/assets/img/admin/hero-3.png',
				'jeg_hero_style_4' => JNEWS_THEME_URL . '/assets/img/admin/hero-4.png',
				'jeg_hero_style_5' => JNEWS_THEME_URL . '/assets/img/admin/hero-5.png',
				'jeg_hero_style_6' => JNEWS_THEME_URL . '/assets/img/admin/hero-6.png',
				'jeg_hero_style_7' => JNEWS_THEME_URL . '/assets/img/admin/hero-7.png',
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true
				)
			)
		);
		$options['hero_margin'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Hero Margin', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Margin of each hero element.', 'jnews-option-category' ),
			'type'       => 'number',
			'options'    => array(
				'min'  => '0',
				'max'  => '30',
				'step' => '1',
			),
			'default'    => 10,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true
				)
			)
		);
		$options['hero_date']   = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Choose Date Format', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose which date format you want to use for category.', 'jnews-option-category' ),
			'default'    => 'default',
			'type'       => 'select',
			'options'    => array(
				'ago'     => esc_html__( 'Relative Date/Time Format (ago)', 'jnews-option-category' ),
				'default' => esc_html__( 'WordPress Default Format', 'jnews-option-category' ),
				'custom'  => esc_html__( 'Custom Format', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true
				)
			)
		);

		$options['hero_date_custom'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Custom Date Format', 'jnews-option-category' ),
			'desc'       => wp_kses( sprintf( __( "Please set custom date format for hero element. For more detail about this format, please refer to
                        <a href='%s' target='_blank'>Developer Codex</a>.", "jnews-option-category" ), "https://developer.wordpress.org/reference/functions/current_time/" ),
				wp_kses_allowed_html() ),
			'default'    => 'Y/m/d',
			'type'       => 'text',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true
				),
				array(
					'field'    => 'hero_date',
					'operator' => '==',
					'value'    => 'custom'
				)
			)
		);

		/**
		 * Override category content
		 */
		$options['content_layout'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Content Layout', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your category content layout.', 'jnews-option-category' ),
			'default'    => '3',
			'type'       => 'radioimage',
			'options'    => $content_layout,
			'dependency' => array(
				$category_override,
				$custom_template,
			)
		);

		$options['content_boxed'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Enable Boxed', 'jnews-option-category' ),
			'desc'       => esc_html__( 'This option will turn the module into boxed.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_layout',
					'operator' => 'in',
					'value'    => array( '3', '4', '5', '6', '7', '9', '10', '14', '18', '22', '23', '25', '26', '27', '39' )
				)
			)
		);

		$options['content_boxed_shadow'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Enable Shadow', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Enable shadow on the module template.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_boxed',
					'operator' => '==',
					'value'    => true
				),
				array(
					'field'    => 'content_layout',
					'operator' => 'in',
					'value'    => array( '3', '4', '5', '6', '7', '9', '10', '14', '18', '22', '23', '25', '26', '27', '39' )
				)
			)
		);

		$options['content_box_shadow'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Enable Shadow', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Enable shadow on the module template.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_layout',
					'operator' => 'in',
					'value'    => array( '37', '35', '33', '36', '32', '38' )
				)
			)
		);

		$options['content_excerpt'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Excerpt Length', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Set the word length of excerpt on post.', 'jnews-option-category' ),
			'type'       => 'number',
			'options'    => array(
				'min'  => '0',
				'max'  => '200',
				'step' => '1',
			),
			'default'    => 20,
			'dependency' => array(
				$category_override,
				$custom_template,
			)
		);

		$options['content_date'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Choose Date Format', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose which date format you want to use for category content element.', 'jnews-option-category' ),
			'default'    => 'default',
			'type'       => 'select',
			'options'    => array(
				'ago'     => esc_html__( 'Relative Date/Time Format (ago)', 'jnews-option-category' ),
				'default' => esc_html__( 'WordPress Default Format', 'jnews-option-category' ),
				'custom'  => esc_html__( 'Custom Format', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
			)
		);

		$options['content_date_custom'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Custom Date Format', 'jnews-option-category' ),
			'desc'       => wp_kses( sprintf( __( "Please set custom date format for category content element. For more detail about this format, please refer to
                                        <a href='%s' target='_blank'>Developer Codex</a>.", "jnews-option-category" ), "https://developer.wordpress.org/reference/functions/current_time/" ),
				wp_kses_allowed_html() ),
			'default'    => 'Y/m/d',
			'type'       => 'text',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_date',
					'operator' => '==',
					'value'    => 'custom'
				)
			)
		);

		$options['content_pagination'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Choose Pagination Mode', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose which pagination mode that fit with your block.', 'jnews-option-category' ),
			'default'    => 'nav_1',
			'type'       => 'select',
			'options'    => array(
				'nav_1'      => esc_html__( 'Normal - Navigation 1', 'jnews-option-category' ),
				'nav_2'      => esc_html__( 'Normal - Navigation 2', 'jnews-option-category' ),
				'nav_3'      => esc_html__( 'Normal - Navigation 3', 'jnews-option-category' ),
				'nextprev'   => esc_html__( 'Ajax - Next Prev', 'jnews-option-category' ),
				'loadmore'   => esc_html__( 'Ajax - Load More', 'jnews-option-category' ),
				'scrollload' => esc_html__( 'Ajax - Auto Scroll Load', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
			)
		);

		$options['content_pagination_limit'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Auto Load Limit', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Limit of auto load when scrolling, set to zero to always load until end of content.', 'jnews-option-category' ),
			'type'       => 'number',
			'options'    => array(
				'min'  => '0',
				'max'  => '9999',
				'step' => '1',
			),
			'default'    => 0,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => '==',
					'value'    => 'scrollload'
				)
			)
		);

		$options['content_pagination_align'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Pagination Align', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose pagination alignment.', 'jnews-option-category' ),
			'default'    => 'center',
			'type'       => 'select',
			'options'    => array(
				'left'   => esc_html__( 'Left', 'jnews-option-category' ),
				'center' => esc_html__( 'Center', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => 'in',
					'value'    => array( 'nav_1', 'nav_2', 'nav_3' )
				)
			)
		);

		$options['content_pagination_text'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Show Navigation Text', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Show navigation text (next, prev).', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => 'in',
					'value'    => array( 'nav_1', 'nav_2', 'nav_3' )
				)
			)
		);

		$options['content_pagination_page'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Show Page Info', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Show page info text (Page x of y).', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => 'in',
					'value'    => array( 'nav_1', 'nav_2', 'nav_3' )
				)
			)
		);


		return apply_filters( 'jnews_custom_option', $options );
	}
}
