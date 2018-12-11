<?php
/**
 * The home template file.
 *
 * @package WordPress
 * @subpackage BirdTHERAPY
 * @since BirdTHERAPY 1.0
 */
get_header(); ?>

<div id="content">
	<?php birdtherapy_content_header(); ?>

	<?php if( ! is_paged()): ?>
		<?php if( !birdtherapy_headerslider()): ?>
			<section id="wall" class="no-image"></section>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( have_posts()) : ?>
		<section id="information" class="section">
			<div class="container">
				<h2 class="content-title">お知らせ</h2>
				<ul class="list">
				<?php while ( have_posts()) : the_post(); ?>
					<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<a href="<?php the_permalink(); ?>" title="<?php printf( '%sへのリンク', the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<header class="entry-header">
								<time class="postdate" datetime="<?php echo get_the_time('Y-m-d') ?>"><?php echo get_post_time( get_option( 'date_format' ) ); ?></time>
								<h3 class="entry-title"><?php the_title(); ?></h3>
							</header>
						</a>
					</li>

				<?php endwhile; ?>
				</ul>
				<?php $category_id = get_cat_ID( 'Information' ); ?>
				<div class="more"><a href="<?php echo get_category_link( $category_id ); ?>">お知らせをもっとみる</a></div>
			</div>
		</section>
	<?php endif; ?>

	<?php
		//プロフィール
		$args = array(
			'post_type'		=> 'page',
			'name'			=> 'about',
			'post_status'	=> 'publish'
		);
		$the_query = new WP_Query($args);
		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) : $the_query->the_post();
	?>

	<section class="section <?php  echo get_post_field( 'post_name', get_the_ID() ); ?>">
		<div class="container">
			<h2 class="content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

			<?php
				$more_text = get_the_title() .'詳しくみる';
				$more_url = get_the_permalink();
			?>

			<?php
				the_content('');
			?>

			<div class="more"><a href="<?php echo $more_url; ?>" class="more"><?php echo $more_text; ?></a></div>
		</div>
	</section>

	<?php endwhile;
		wp_reset_postdata();
		endif;
	?>



	<?php birdtherapy_content_footer(); ?>
</div>

<?php get_footer(); ?>
_____