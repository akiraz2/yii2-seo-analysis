<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

/**
* @var yii\web\View $this
* @var common\models\SiteProject $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="site-project-form">

    <?php $form = ActiveForm::begin([
    'id' => 'SiteProject',
    'layout' => 'default',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-danger',
    'fieldConfig' => [
             'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
             'horizontalCssClasses' => [
                 'label' => 'col-sm-2',
                 #'offset' => 'col-sm-offset-4',
                 'wrapper' => 'col-sm-8',
                 'error' => '',
                 'hint' => '',
             ],
         ],
    ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>
            

<!-- attribute name -->
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<!-- attribute base_url -->
			<?= $form->field($model, 'base_url')->textInput(['maxlength' => true]) ?>

<!-- attribute ping -->
			<?= $form->field($model, 'ping')->textInput() ?>

<!-- attribute reindex -->
			<?= $form->field($model, 'reindex')->textInput() ?>

<!-- attribute ping_last_date -->
			<?= $form->field($model, 'ping_last_date')->textInput() ?>

<!-- attribute params -->
			<?= $form->field($model, 'params')->textarea(['rows' => 6]) ?>

<!-- attribute status -->
			<?= $form->field($model, 'status')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                    'encodeLabels' => false,
                    'items' => [ 
                        [
    'label'   => Yii::t('app-model', 'SiteProject'),
    'content' => $this->blocks['main'],
    'active'  => true,
],
                    ]
                 ]
    );
    ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?= Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> ' .
        ($model->isNewRecord ? Yii::t('app-model', 'Create') : Yii::t('app-model', 'Save')),
        [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
        ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>

