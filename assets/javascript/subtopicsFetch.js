$(function () {
    const $topicSelect = $('#question_form_topic');
    const $specificTopicTarget = $('#js-specific-topic-target');

    $topicSelect.change(function (e) {
        $.ajax({
            url: $topicSelect.data('specific-topic-url'),
            data: {topic: $topicSelect.val()},
            method: 'GET',
            dataType: 'html'
        }).done(
            function (html) {
                if (!html) {
                    $specificTopicTarget.children().remove();
                } else {
                    $specificTopicTarget.html(html);
                }
            }
        )
        ;
    });
})