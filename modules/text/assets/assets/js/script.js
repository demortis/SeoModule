/**
 * Created by eugene on 05.04.16.
 */

/*МЕНЮ*/
$(document).on('mouseenter mouseleave', 'div.sm-text-list-item-control', function(){
    $(this).find('.sm-text-list-item-menu')
        .show(300)
        .toggleClass('sm-text-list-item-menu-open');
});
$(document).on('mouseleave', 'div.sm-text-list-item-control', function(){
    $(this).find('.sm-text-list-item-menu')
        .hide(100);
});

$(document).on('click', 'div.sm-text-list-item-menu a', function(){
    if($(this).data('action') === 'delete'){
        var conf = confirm('Вы уверены что хотите удалить этот текст?');
        if(!conf) return false;
    }


});
/*МЕНЮ*/

$('#seotext-text_type_id').on('change', function(){
    var $this = $(this),
        val = $this.val(),
        hostField = $("#seotext-origin_id");

    if(val === '') return;

    if(hostField.length > 0){
        if(hostField.val() === ''){
            $this.val('');
            alert("Выберите проект."); return;
        }
    }

    $.ajax({
        url : 'form?id=' + val,
        success : function(data){
            $('#form-box').html(data);
        }
    });
});

$('#seo-text-update-status').on('change', 'input[type=checkbox]', function(event){
    $.ajax({
        type: 'POST',
        data : $(event.delegateTarget).serialize()
    });
});

$(document).on('change', '#seotext-template_id', function(){
    var val = $(this).val(),
        targetBlock = $('.seo-text-update-template-block');

    if(val == '')
    {
        targetBlock.html('');
        return;
    }

    $.ajax({
        url : 'template?id='+val,
        success : function(data){
            targetBlock.html(data);
            $('[data-template-id]').data('template-id', val);
        }
    });
});

$(document).on('click', '#template-edit-but', function(){
    var modal = $('#template-modal'),
        templateId = $(this).data('template-id');

    $.ajax({
        url : '/seo/texts/text-template/update?id='+templateId,
        success : function(data){
            modal.find('.modal-body').html(data);
            modal.modal('show');
        }
    });

    return false;
});

$(document).on('click', '#template-remove-but', function(){
    var modal = $('#template-modal'),
        templateId = $(this).data('template-id'),
        conf = confirm('Вы уверены что хотите удалить выбранный шаблон?');

    if(!conf) return false;

    $.ajax({
        url : '/seo/texts/text-template/delete?id='+templateId,
        dataType : "JSON",
        success : function(data){
            if(data instanceof Object)
                $('#seotext-template_id').find('option[value='+data.id+']').remove();
            $('#seotext-template_id').change();
        }
    });

    return false;
});

$(document).on('click', '.but-remove', function(){
    var conf = confirm('Вы уверены что хотите удалить переменную шаблона?');
    if(conf) $($(this).data('target')).remove();

    return false;
});

$(document).on('click', '#new-template-modal-but', function(){
    var modal = $('#template-modal');
    $.ajax({
        url : '/seo/texts/text-template/create',
        success : function(data){
            modal.find('.modal-body').html(data);
            modal.modal('show');
        }
    });
});

$(document).on('keyup', '#seotext-title', function(){
    var title = $(this).val(),
        sliced = title.slice(0, 47);
    if(sliced.length < title.length)
        sliced += '...';

    $('.article-preview-title-box').text(sliced);
});

$(document).on('change', 'input[name=article-preview-img]', function(){
    var $this = $(this),
        val = $this.val(),
        files = this.files,
        hashBlock = $('#seotext-temphash'),
        hashString = hashBlock.length > 0 ? '&tempHash=' + hashBlock.val() : '',
        idString = getUrlVars()['id'] !== undefined ? '&id=' + getUrlVars()['id'] : '',
        hostField = $("#seotext-origin_id"),
        origin = hostField.length !== 0 && hostField.val() !== '' ? '&origin=' + hostField.find("option:selected").text().replace("http://", "") : '';

    if(val == '') return;

    var data = new FormData();

    $.each(files, function(key, value){
        data.append('upload', value);
    });
    $.ajax({
        url: 'image-upload?source=preview'+ hashString + idString + origin,
        data: data,
        processData: false,
        contentType: false,
        type: 'POST',
        beforeSend : function() {
            $('.article-preview-image-box > img').remove();
        },
        success: function(result){
            if(result != '')
                $('.article-preview-image-box').prepend('<img src="'+result+'">');
        }
    });

});

$('#seo-text-update').on('click', 'button.submit', function(e){
    $.ajax({
        type : 'POST',
        data : $(e.delegateTarget).serialize(),
        dataType : 'JSON',
        beforeSend : clearErrors(),
        success :function (data) {
            if(data instanceof Object)
                catchErrors(data);
        }
    });
});
function clearErrors() {
    $('.help-block').empty();
}
function catchErrors(data){
    for(var attribute in data)
    {
        $('input[id$='+attribute+'], textarea[id$='+attribute+']')
                .nextAll('.help-block')
                .text(data[attribute])
                .parent()
                .addClass('has-error');
    }
}

function addVarField(obj)
{
    var key = $('input[name^=template-var]').length / 2,
        fieldTpl = '<div class="form-group">' +
            '<div class="row" id="var-'+key+'">' +
            '<div class="col-md-5">' +
            '<input type="text" name="template-var['+key+'][name]" class="form-control">' +
            '</div>' +
            '<div class="col-md-5">' +
            '<input type="text" name="template-var['+key+'][value]" class="form-control">' +
            '</div>' +
            '<div class="col-md-2">' +
            '<div class="row">' +
            '<a href="#" class="but-remove" data-target="#var-'+key+'"><span class="glyphicon glyphicon-remove"></span></a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    $(obj).closest('.form-group').before(fieldTpl);
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}