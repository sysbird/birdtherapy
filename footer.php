<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage BirdTHERAPY
 * @since BirdTHERAPY 1.0
 */
?>
	<div id="widget-area">
		<div class="container">
			<div class="widget-area-left">
				<?php dynamic_sidebar( 'widget-area-footer-left' ); ?>
			</div>
			<div class="widget-area-center">
				<?php dynamic_sidebar( 'widget-area-footer-center' ); ?>
			</div>
			<div class="widget-area-right">
				<?php dynamic_sidebar( 'widget-area-footer-right' ); ?>
			</div>
		</div>
	</div>

	<footer id="footer">
		<div class="container">
			<p class="copyright">
				<?php printf( '&copy; %s Sasaki Acupuncture Treatment and Massage Therapy Center All Rights Reserved.  ', date("Y") ); ?>
			</p>
		</div>
		<p id="back-top"><a href="#top"><span>ページトップへ</span></a></p>
	</footer>

</div><!-- wrapper -->

<?php wp_footer(); ?>

<span style="display: none;">
	<?php echo do_shortcode( '[bogo]' ); ?>
</span>

</body>
</html>