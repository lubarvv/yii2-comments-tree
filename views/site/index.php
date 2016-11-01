<?php

/* @var $this yii\web\View */

$this->title = 'Yii2 comments tree';

\app\assets\CommentsAsset::register($this);
?>

<h2 id="comments-loading"><i class="fa fa-spin fa-spinner"></i> Loading comments...</h2>

<div id="tree-comments-wrap"></div>
<div id="commentFormWrapper_0">
    <div class="tree-comment-form"></div>
</div>

<?= $this->render('templates/comment'); ?>
<?= $this->render('templates/form'); ?>