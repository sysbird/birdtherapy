<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage BirdTHERAPY
 * @since BirdTHERAPY 1.0
 */

$birdtherapy_type = get_post_type( $post );
$birdtherapy_archive_url = '';
$birdtherapy_archive_title = '';

get_header(); ?>

<div id="content">
	<?php birdtherapy_content_header(); ?>

	<div class="container">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<?php if( is_single()):
						if( "post" === $birdtherapy_type ){
							$cat = get_the_category();
							if( count( $cat ) ){
								$birdtherapy_archive_url = get_category_link( $cat[0]->cat_ID );
								$birdtherapy_archive_title = $cat[0]->name;
							}
						}	
						else{
							if( "works" == $birdtherapy_type ){
								$terms = get_the_terms( $post->ID, 'works-genre' );
								foreach( $terms as $term ) {
									$birdtherapy_archive_url = get_term_link( $term->slug, 'works-genre' );
									$birdtherapy_archive_title = $term->name;
									break;
								}
							}
							else{
								$birdtherapy_archive_url = get_post_type_archive_link( $birdtherapy_type );
								$birdtherapy_archive_title = get_post_type_object( $birdtherapy_type )->label;
							}
						}
						?>

						<span class="cateogry"><a href="<?php echo esc_url( $birdtherapy_archive_url ); ?>"><?php echo esc_html( $birdtherapy_archive_title ); ?></a></span>
			
						<?php if( ("works" != $birdtherapy_type ) && birdtherapy_is_recently()): ?>
							<time class="postdate" datetime="<?php echo get_the_time( 'Y-m-d' ) ?>"><?php echo get_post_time( __( 'F j, Y', 'birdtherapy')); ?></time>
						<?php endif; ?>
					<?php endif; ?>

				</header>

				<?php if( 'works' === $birdtherapy_type ): ?>
					<div class="two-columns">
						<div class="left-colum">
							<?php if( has_post_thumbnail() ): ?>
								<div class="entry-eyecatch birdtherapy-photos-cover">
									<?php the_post_thumbnail( 'middle' ); ?>
								</div>
							<?php endif; ?>
						</div>
				<?php endif; ?>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>

				<?php if( 'works' === get_post_type()): ?>
					</div>
				<?php endif; ?>

			</article>

			<?php if( is_single()): ?>
				<?php $birdtherapy_archive_title .= 'をもっとみる'; ?>
				<div class="more"><a href="<?php  _e( esc_url( $birdtherapy_archive_url, 'birdtherapy' )); ?>"><?php _e( esc_html( $birdtherapy_archive_title ), 'birdtherapy' ) ?></a></div>

				<nav class="nav-below">
					<span class="nav-previous"><?php previous_post_link('%link', '%title' ); ?></span>
					<span class="nav-next"><?php next_post_link('%link', '%title' ); ?></span>
				</nav>
			<?php endif; ?>

		<?php endwhile; ?>
	</div>

	<?php birdtherapy_content_footer(); ?>
</div>

<?php get_footer(); ?>