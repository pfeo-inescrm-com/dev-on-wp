<?php
/*
 *	Deprecated Core Theming functions
 */

/**
 * Function to check if the single product mode is activated
 *
 * @since 1.3.0
 *
 * @return bool
 */
function basepress_is_single_product() {
	return basepress_is_single_kb();
}

/**
 * Gets all products objects
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function basepress_products() {
	return basepress_kbs();
}

/**
 * Gets single current product
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function basepress_product() {
	return basepress_kb();
}

/**
 * Echos the number of columns set in the options for the products page
 *
 * @since 1.0.0
 */
function basepress_product_cols() {
	return basepress_kb_cols();
}