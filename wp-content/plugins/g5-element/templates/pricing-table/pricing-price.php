<?php
/**
 * @var $currency_code_typography
 * @var $currency_code
 * @var $price_typography
 * @var $price
 * @var $duration_typography
 * @var $duration
 * @var $layout_style
 */
?>

<?php
$currency_code_typography = g5element_typography_class($currency_code_typography);
$currency_classes         = array('pricing-price-currency');
if ($currency_code_typography !== '') {
	$currency_classes[] = $currency_code_typography;
}

$price_typography  = g5element_typography_class($price_typography);
$price_num_classes = array('pricing-price-number');
if ($price_typography !== '') {
	$price_num_classes[] = $price_typography;
}

$duration_typography = g5element_typography_class($duration_typography);
$duration_classes    = array('pricing-price-duration');
if ($duration_typography !== '') {
	$duration_classes[] = $duration_typography;
}

$price_classes = array('pricing-price');

?>

<?php if ($price !== ''): ?>
	<div class="pricing-price">
		<div class="price">
			<h2 class="<?php echo esc_attr(join(' ', $currency_classes)) ?>">
				<?php echo esc_html($currency_code) ?>
			</h2>
			<h2 class="<?php echo esc_attr(join(' ', $price_num_classes)) ?>">
				<?php echo esc_html($price) ?>
			</h2>
		</div>
		<span class="<?php echo esc_attr(join(' ', $duration_classes)) ?>">
				<?php echo esc_html($duration) ?>
			</span>
	</div>
<?php endif; ?>

