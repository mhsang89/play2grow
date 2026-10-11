<?php
/**
 * Multilingual support: WPML (used on play2grow.edu.vn) and Polylang.
 * Without either plugin every function degrades to single-language behaviour.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Active multilingual plugin: "wpml", "polylang" or "".
 *
 * @return string
 */
function p2g_ml_plugin() {
	if ( defined( 'ICL_SITEPRESS_VERSION' ) && has_filter( 'wpml_object_id' ) ) {
		return 'wpml';
	}
	if ( function_exists( 'pll_get_post' ) ) {
		return 'polylang';
	}

	return '';
}

/**
 * Home URL of the current language (Polylang only filters home_url() in some
 * contexts, so theme links use this helper).
 *
 * @param string $path Optional path or query relative to the home URL, e.g. "?s=".
 * @return string
 */
function p2g_home_url( $path = '' ) {
	$plugin = p2g_ml_plugin();
	$home   = home_url( '/' );
	if ( 'polylang' === $plugin && function_exists( 'pll_home_url' ) ) {
		$home = pll_home_url();
	} elseif ( 'wpml' === $plugin ) {
		$home = apply_filters( 'wpml_home_url', $home );
	}

	return $path ? trailingslashit( $home ) . ltrim( $path, '/' ) : $home;
}

/**
 * Current language code ("vi", "en"…). During the starter import this is the
 * language being imported.
 *
 * @return string
 */
function p2g_current_lang() {
	if ( ! empty( $GLOBALS['p2g_import_lang'] ) ) {
		return $GLOBALS['p2g_import_lang'];
	}
	$plugin = p2g_ml_plugin();
	if ( 'wpml' === $plugin ) {
		$lang = apply_filters( 'wpml_current_language', null );
	} elseif ( 'polylang' === $plugin ) {
		$lang = pll_current_language();
	} else {
		$lang = substr( determine_locale(), 0, 2 );
	}

	return $lang ? (string) $lang : 'vi';
}

/**
 * ID of the translation of a post/page/term in a language (current language by default).
 *
 * @param int    $id   Object ID.
 * @param string $type "page", "post", "category"… (post type or taxonomy).
 * @param string $lang Language code; '' = current.
 * @return int Translated ID, or the original ID when no translation exists.
 */
function p2g_translate_id( $id, $type = 'page', $lang = '' ) {
	$id = (int) $id;
	if ( ! $id ) {
		return 0;
	}
	$lang   = $lang ? $lang : p2g_current_lang();
	$plugin = p2g_ml_plugin();
	$is_tax = taxonomy_exists( $type );

	if ( 'wpml' === $plugin ) {
		$tid = apply_filters( 'wpml_object_id', $id, $type, true, $lang );
		return $tid ? (int) $tid : $id;
	}
	if ( 'polylang' === $plugin ) {
		$tid = $is_tax ? pll_get_term( $id, $lang ) : pll_get_post( $id, $lang );
		return $tid ? (int) $tid : $id;
	}

	return $id;
}

/**
 * Languages for the switcher: [code => [name, url, current]].
 *
 * @return array
 */
function p2g_languages() {
	$out    = array();
	$plugin = p2g_ml_plugin();

	if ( 'wpml' === $plugin ) {
		$langs = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
		foreach ( (array) $langs as $code => $lang ) {
			$out[ $code ] = array( strtoupper( $code ), $lang['url'], ! empty( $lang['active'] ), isset( $lang['native_name'] ) ? $lang['native_name'] : $code );
		}
	} elseif ( 'polylang' === $plugin && function_exists( 'pll_the_languages' ) ) {
		$langs = pll_the_languages(
			array(
				'raw'           => 1,
				'hide_if_empty' => 0,
			)
		);
		foreach ( (array) $langs as $lang ) {
			$out[ $lang['slug'] ] = array( strtoupper( $lang['slug'] ), $lang['url'], ! empty( $lang['current_lang'] ), $lang['name'] );
		}
	}

	return count( $out ) > 1 ? $out : array();
}

/**
 * Print the language switcher (VI | EN).
 *
 * @param string $context "topbar" or "nav".
 */
function p2g_language_switcher( $context = 'topbar' ) {
	$langs = p2g_languages();
	if ( ! $langs ) {
		return;
	}
	echo '<nav class="lang-switch lang-switch--' . esc_attr( $context ) . '" aria-label="' . esc_attr__( 'Ngôn ngữ', 'play2grow-theme' ) . '"><ul>';
	foreach ( $langs as $code => $lang ) {
		list( $label, $url, $current, $name ) = $lang;
		printf(
			'<li><a href="%1$s" lang="%2$s" hreflang="%2$s"%3$s><span aria-hidden="true">%4$s</span><span class="screen-reader-text">%5$s</span></a></li>',
			esc_url( $url ),
			esc_attr( $code ),
			$current ? ' aria-current="true" class="is-current"' : '',
			esc_html( $label ),
			esc_html( $name )
		);
	}
	echo '</ul></nav>';
}

/**
 * Open Graph locale for the current language.
 *
 * @return string e.g. "vi_VN", "en_US".
 */
function p2g_og_locale() {
	$locale = determine_locale();
	if ( 'vi' === $locale ) {
		$locale = 'vi_VN';
	}
	if ( 2 === strlen( $locale ) ) {
		$locale = 'en' === $locale ? 'en_US' : $locale . '_' . strtoupper( $locale );
	}

	return $locale;
}
