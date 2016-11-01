<div id="commentTemplate" class="hidden">
    <div class="tree-comment-wrap" id="comment_{{id}}">
        <div class="tree-comment">
            <div class="tree-comment-head">
                <span class="username">{{username}}</span>
                <span class="date">{{date}}</span>
                {{^isDeleted}}
                <span class="actions">
                    <a href="{{updateUrl}}"
                       data-comment-id="{{id}}"
                       data-parent-comment-id="{{parentCommentId}}"
                       class="edit"
                       title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="{{deleteUrl}}" class="delete" title="Delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </span>
                {{/isDeleted}}
            </div>

            <div class="tree-comment-text {{#isDeleted}}deleted{{/isDeleted}}">{{text}}</div>

            <div id="commentEditForm_{{id}}" class="tree-comment-form"></div>

            <div id="commentFormWrapper_{{id}}"  class="tree-comment-add">
                <a href="#" class="tree-comment-add-link" data-comment-id="{{id}}">
                    <i class="fa fa-plus"></i> Add comment
                </a>
                <a href="#" class="tree-comment-cancel-link hidden" data-comment-id="{{id}}">
                    <i class="fa fa-undo"></i> Cancel
                </a>
                <div class="tree-comment-form"></div>
            </div>
        </div>

        {{#isRootWithChildren}}
        <div class="tree-comments-load-children" data-comment-id="{{id}}">
            <span class="show-comments">
                <i class="fa fa-plus-circle"></i> Show children
            </span>
        </div>
        {{/isRootWithChildren}}

        <div id="comment_children_{{id}}" class="tree-comments"></div>

    </div>
</div>