<?php
use yii\helpers\Html;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-sm-8 block-img">
        <?php
        $DIR = '/content/cars/';
        foreach ($photos AS $num=>$photo){
            if($num == 0)
                echo '<div class="col-sm-12"><img src="'.$DIR.'720x540/'.$photo.'"/></div>';
            else
                echo '<div class="col-sm-2"><img src="'.$DIR.'146x106/'.$photo.'"/></div>';
        }
        ?>
    </div>
    <div class="col-sm-4">
        <div><strong>Цена:</strong> <?=$car['price']?></div>
        <?php
        if(!empty($car['mileage'])){ ?>
            <div><strong>Пробег:</strong> <?=$car['mileage']?></div>
        <?php
        } ?>
        <div><strong>Телефон:</strong> <?=$car['phone']?></div>
        <?php
        foreach ($equipments AS $equipment){?>
            <div><strong><?=$equipment?>:</strong> Есть</div>
        <?php
        } ?>
    </div>
</div>
