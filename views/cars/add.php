<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="cars-add">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'
        ],]); ?>

        <?= $form->field($car, 'id_brand')->dropDownList(
                ArrayHelper::map($car->getBrands(), 'id_brand', 'name'),
                ['prompt'=>'Выберите марку']); ?>
        <?= $form->field($car, 'id_model')->dropDownList(
                ArrayHelper::map($car->getModels($car->id_brand), 'id_model', 'name'),
                ['prompt'=>'Выберите модель']); ?>
        <?= $form->field($car, 'price'); ?>
        <?= $form->field($car, 'phone'); ?>
        <?= $form->field($car, 'mileage'); ?>
        <?= $form->field($car, 'equipments')->checkboxList(
                ArrayHelper::map($car->getEquipments(), 'id_equipment', 'name')); ?>
        <?= $form->field($car, 'images[]')->fileInput(['multiple' => true]);?>
        <div class="form-group">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- cars-add -->
