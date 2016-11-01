(function ($) {

    var Comments = {

        init: function () {
            Comments.ui.binds.all();
            Comments.ui.forms.createRootCommentCreateForm();
            Comments.tree.loadRoots();
        },

        ui: {

            binds: {
                all: function () {
                    Comments.ui.binds.loadChildrenButton();
                    Comments.ui.binds.addCommentToggleButton();
                    Comments.ui.binds.editButton();
                    Comments.ui.binds.cancelEditButton();
                    Comments.ui.binds.deleteButton();
                },

                editButton: function() {
                    $(document).on('click', '.actions .edit', Comments.ui.forms.createCommentEditForm);
                },

                cancelEditButton: function() {
                    $(document).on('click', '.edit-comment-cancel-link', Comments.ui.forms.cancelCommentEditForm);
                },

                loadChildrenButton: function () {
                    $(document).on('click', '.tree-comments-load-children', Comments.tree.loadChildren);
                },

                addCommentToggleButton: function () {
                    $(document).on('click', '.tree-comment-add-link, .tree-comment-cancel-link', Comments.ui.binds.addCommentToggle);
                },

                addCommentToggle: function (event) {
                    event.preventDefault();

                    var commentId = $(this).attr('data-comment-id');

                    if($(this).hasClass('tree-comment-add-link')) {
                        Comments.ui.forms.createCommentCreateForm(commentId);
                    } else {
                        Comments.ui.forms.deleteCommentCreateForm(commentId);
                    }
                },

                deleteButton: function () {
                    $(document).on('click', '.actions .delete', Comments.actions.delete);
                }
            },

            forms: {
                createRootCommentCreateForm: function() {
                    Comments.ui.forms.createCommentCreateForm(0);
                },

                createCommentCreateForm: function(commentId) {
                    var commentForm = $('#commentFormTemplate');
                    var commentFormTemplate = commentForm.html();
                    var createUrl = commentForm.attr('data-create-url');

                    var commentFormHtml = Mustache.render(
                        commentFormTemplate,
                        {actionUrl: createUrl, parentCommentId: commentId, updateForm: false}
                    );


                    var container = $('#commentFormWrapper_' + commentId);

                    container.find('.tree-comment-add-link').toggleClass('hidden');
                    container.find('.tree-comment-cancel-link').toggleClass('hidden');

                    var commentFormWrapper = container.find('.tree-comment-form');
                    commentFormWrapper.append(commentFormHtml);

                    if(commentId == 0) {
                        commentFormWrapper.prepend('<h3>Add comment</h3>')
                    }

                    $('#commentForm_' + commentId).ajaxForm(Comments.actions.add);

                },

                deleteCommentCreateForm: function(commentId) {
                    var container = $('#commentFormWrapper_' + commentId);

                    container.find('.tree-comment-add-link').toggleClass('hidden');
                    container.find('.tree-comment-cancel-link').toggleClass('hidden');
                    container.find('.tree-comment-form').html('');
                },

                createCommentEditForm: function(event) {
                    event.preventDefault();

                    var updateUrl = $(this).attr('href');
                    var commentId = $(this).attr('data-comment-id');
                    var parentCommentId = $(this).attr('data-parent-comment-id');
                    var username = $('#comment_' + commentId + ' .username').html();
                    var text = $('#comment_' + commentId + ' .tree-comment-text').html();

                    var commentFormTemplate = $('#commentFormTemplate').html();
                    var commentFormHtml = Mustache.render(
                        commentFormTemplate,
                        {
                            actionUrl: updateUrl,
                            id: commentId,
                            parentCommentId: parentCommentId,
                            updateForm: true,
                            username: username,
                            text: text
                        }
                    );

                    $('#commentEditForm_' + commentId).html(commentFormHtml);
                    $('#commentForm_' + commentId).ajaxForm(Comments.actions.update);

                },

                cancelCommentEditForm: function(event) {
                    event.preventDefault();

                    $(this).closest('.add-comment-form').remove();
                },

                deleteCommentEditForm: function(commentId) {
                    $('#comment_' + commentId + ' .add-comment-form').remove();
                }
            }
        },

        actions: {
            delete: function () {
                event.preventDefault();

                if (!confirm('Delete this comment?')) {
                    return;
                }

                var link = $(this);

                $.getJSON(
                    link.attr('href'),
                    function (response) {
                        if (!response.result) {
                            alert(response.error);
                            return;
                        }

                        var commentWrapper = link.closest('.tree-comment');
                        commentWrapper.find('.tree-comment-text').addClass('deleted').html('Comment has been deleted');
                        commentWrapper.find('.actions').remove();
                    }
                )
            },

            add: function (response) {

                if (!response.result) {
                    alert(response.error);
                    return;
                }

                Comments.tree.render(response.data.comments);

                var parentCommentId = response.data.comments[0].parentCommentId;
                Comments.ui.forms.deleteCommentCreateForm(parentCommentId);
            },

            update: function (response) {
                if (!response.result) {
                    alert(response.error);
                    return;
                }

                var commentWrapper = $('#comment_' + response.data.comment.id);
                commentWrapper.find('.username').html(response.data.comment.username);
                commentWrapper.find('.tree-comment-text').html(response.data.comment.text);

                Comments.ui.forms.deleteCommentEditForm(response.data.comment.id);
            }
        },

        tree: {
            loadRoots: function() {
                $.getJSON(
                    '/comments/roots',
                    function (response) {
                        if (!response.result) {
                            alert('Error while loading comments');
                            return;
                        }

                        $('#comments-loading').addClass('hidden');
                        Comments.tree.render(response.data.comments);
                    }
                );
            },

            loadChildren: function (event) {
                event.preventDefault();

                var commentId = $(this).attr('data-comment-id');
                var button = $(this);

                $.getJSON(
                    '/comments/children/' + commentId,
                    function (response) {
                        if (!response.result) {
                            alert('Error while loading comments');
                            return;
                        }

                        button.remove();
                        Comments.tree.render(response.data.comments);
                    }
                );
            },

            render: function (comments) {

                var commentTemplate = $('#commentTemplate').html();

                for (var i = 0; i < comments.length; i++) {
                    var comment = comments[i];
                    var commentHtml = Mustache.render(commentTemplate, comments[i]);
                    var parentSelector = comment.isRoot ?
                        '#tree-comments-wrap' :
                        '#comment_children_' + comment.parentCommentId;

                    $(parentSelector).append(commentHtml);
                }
            }
        }

    };

    $(document).ready(Comments.init);
})(jQuery);