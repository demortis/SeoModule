<?php
/**
 * @author: Eugene
 * @date: 07.04.16
 * @time: 9:24
 */

$form = \yii\widgets\ActiveForm::begin([
    'id' => 'update-template-form'
]);
echo $form->field($model, 'text')->widget(\dosamigos\ckeditor\CKEditor::className());

echo \yii\helpers\Html::button('Сохранить изменения', ['class' => 'btn btn-success']);
\yii\widgets\ActiveForm::end();


$this->registerJs('
        $("#update-template-form").on("click", "button", function(event){
            $.ajax({
                url : "/seo/text-template/update?id="+'.$model->id.',
                type : "POST",
                data : $(event.delegateTarget).serialize(),
                dataType : "JSON",
                success : function(data){
                    if(data instanceof Object){
                        $("#seotext-template_id").find("option[value=\"+data.id+\"]").html(data.text);
                        $("#seotext-template_id").change();
                        $("#template-modal").modal("hide");
                    }
                }
            })
        });
    ');
?>
