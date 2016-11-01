<?php
use yii\helpers\Url;

?>
<div id="commentFormTemplate" class="hidden" data-create-url="<?= Url::to(['comments/create']) ?>">
    <form
        class="add-comment-form"
        id="commentForm_{{#updateForm}}{{id}}{{/updateForm}}{{^updateForm}}{{parentCommentId}}{{/updateForm}}"
        action="{{actionUrl}}"
        method="post">
        {{^updateForm}}
        <input type="hidden" name="Comment[parentCommentId]" value="{{parentCommentId}}"/>
        {{/updateForm}}
        <div class="form-group">
            <input type="text" class="form-control" name="Comment[userName]" placeholder="Your name" value="{{username}}"/>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="Comment[text]" rows="2" placeholder="Comment text">{{text}}</textarea>
        </div>
        <button class="btn btn-primary add-comment-button">
            {{#updateForm}}Update comment{{/updateForm}}
            {{^updateForm}}Add comment{{/updateForm}}
        </button>
        {{#updateForm}}
        <a href="#" class="edit-comment-cancel-link">
            <i class="fa fa-undo"></i> Cancel
        </a>
        {{/updateForm}}
    </form>
</div>