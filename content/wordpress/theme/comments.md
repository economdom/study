# Комментарии

Отображение комментариев в WordPress определяются в настройках и шаблоне *comments.php*.

*comments.php*

```php
<?php
//Get only the approved comments 
$args = array(
    'status' => 'approve'
);

// The comment Query
$comments_query = new WP_Comment_Query;
$comments = $comments_query->query( $args );

// Comment Loop
if ( $comments ) {
    foreach ( $comments as $comment ) {
        echo '<p>' . $comment->comment_content . '</p>';
    }
} else {
    echo 'No comments found.';
}
?>
```

В файле *single.php* в месте где нужно выводить список комментарией, нужно сделать проверку открыты ли комментарии вообще и имееются ли собщения в БД:

*single.php*

```php
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) :
    comments_template();
endif;
```