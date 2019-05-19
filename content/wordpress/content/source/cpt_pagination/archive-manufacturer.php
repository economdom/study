<?php
get_header() ?>
<?php
$args = array(
    'posts_per_page' => 2,
    'post_type'=>'manufacturer',
    'paged' => get_query_var('paged')
);
$query = new WP_Query( $args); 
?>
<?php if ( $query->have_posts() ): ?>
    <?php while ($query -> have_posts()) : $query -> the_post(); ?>
        <?php
        $producer_terms = get_the_terms( get_the_ID(), 'producer' );
        $producer_term_meta = get_term_meta($producer_terms[0]->term_id);
        $attachment_logo_id = $producer_term_meta['producer_logo'][0];
        $attachment_flag_id = $producer_term_meta['producer_flag'][0];
        ?>
        <a href="<?php echo get_term_link($producer_terms[0]->term_id) ?>" class="producer_item">
            <span>
                <?php echo wp_get_attachment_image($attachment_logo_id, 'thumbnail'); ?>
            </span>
            <?php echo wp_get_attachment_image($attachment_flag_id, 'thumbnail'); ?>
        </a>
    <?php endwhile;?>
    <?php
    $big = 999999999;
    echo paginate_links(
        array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $query->max_num_pages,
            'prev_text' => __('<i class="fa fa-angle-left"></i>'),
            'next_text' => __('<i class="fa fa-angle-right"></i>'),
        )
    );
    ?>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>
<?php get_footer() ?>