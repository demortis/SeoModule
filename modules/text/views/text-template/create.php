<?php
/**
 * @author: Eugene
 * @date: 06.04.16
 * @time: 15:18
 */

    $form = \yii\widgets\ActiveForm::begin([
        'id' => 'new-template-form'
    ]);
        echo $form->field($model, 'text')->widget(\dosamigos\ckeditor\CKEditor::className());

        echo \yii\helpers\Html::button('Сохранить шаблон', ['class' => 'btn btn-success']);
    \yii\widgets\ActiveForm::end();


    $this->registerJs('
        $("#new-template-form").on("click", "button", function(event){
            $.ajax({
                url : "/seo/texts/text-template/create",
                type : "POST",
                data : $(event.delegateTarget).serialize(),
                dataType : "JSON",
                success : function(data){
                    if(data instanceof Object){
                        $("#template-modal").modal("hide");
                        $("#seotext-template_id").append("<option value=\'"+data.id+"\'>"+data.text+"</option>");
                    }
                }
            })
        });
    ');
?>
