<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

//$this->registerJsFile('js/point.js');

/* @var $this yii\web\View */
/* @var $model app\models\Point */
/* @var $form ActiveForm */
?>
<div class="point">

    <?php $form = ActiveForm::begin(['method' => 'get']); ?>

    <?= $form->field($model, 'longitude') ?>
    <?= $form->field($model, 'latitude') ?>
    <?= $form->field($model, 'radius') ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- point -->
