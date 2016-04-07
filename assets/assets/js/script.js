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


$('#seo-text-update-status').on('change', 'input[type=checkbox]', function(event){
    $.ajax({
        type: 'POST',
        data : $(event.delegateTarget).serialize()
    });
});

$('#seotext-template_id').on('change', function(){
    var val = $(this).val(),
        targetBlock = $('.seo-text-update-template-block');

    if(val == '')
    {
        targetBlock.html('');
        return;
    }

    $.ajax({
        url : '/seo/text/get-template?id='+val,
        success : function(data){
            targetBlock.html(data);
            $('[data-template-id]').data('template-id', val);
        }
    });
});

$('#template-edit-but').on('click', function(){
    var modal = $('#template-modal'),
        templateId = $(this).data('template-id');

    $.ajax({
        url : '/seo/text-template/update?id='+templateId,
        success : function(data){
            modal.find('.modal-body').html(data);
            modal.modal('show');
        }
    });

    return false;
});

$('#template-remove-but').on('click', function(){
    var modal = $('#template-modal'),
        templateId = $(this).data('template-id'),
        conf = confirm('Вы уверены что хотите удалить выбранный шаблон?');

    if(!conf) return false;

    $.ajax({
        url : '/seo/text-template/delete?id='+templateId,
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

$('#new-template-modal-but').on('click', function(){
    var modal = $('#template-modal');
    $.ajax({
        url : '/seo/text-template/create',
        success : function(data){
            modal.find('.modal-body').html(data);
            modal.modal('show');
        }
    });
});

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

