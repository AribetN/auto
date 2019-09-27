<?php
use yii\helpers\Url;
use yii\data\Pagination;
use yii\helpers\Html;
?>

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row head">
        <div class="col-sm-3">Марка, модель</div>
        <div class="col-sm-3">Цена</div>
        <div class="col-sm-3">Фото</div>
    </div>
<?php
$DIR = '/content/cars/146x106/';
foreach ($cars AS $car){ ?>
    <div class="row item-car">
        <div class="col-sm-3">
            <a href="<?=Url::to(['cars/car', 'id_car'=>$car['id_car']])?>" title="<?=$car['brand_name'].' '.$car['model_name']?>">
                <?=$car['brand_name'].' '.$car['model_name']?>
            </a>
        </div>
        <div class="col-sm-3"><?=$car['price']?></div>
        <div class="col-sm-3">
<?php
    if(!empty($car['file_name']))
        echo '<img src="'.$DIR.$car['file_name'].'"/>';
?>
        </div>
        <div class="col-sm-3">
            <a class="pull-right" href="<?=Url::to(['cars/delete', 'id_car'=>$car['id_car']])?>" title="Удалить">Удалить</a>
        </div>
    </div>
<?php
}
$pagination = new Pagination(['totalCount' => $count, 'defaultPageSize'=>$pageSize]);

echo \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]);
?>
