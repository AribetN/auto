<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Cars;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CarsController extends Controller
{
    public function actionIndex($page = 1){
        $this->view->title = 'Все объявления';
        $page = (int)$page;
        $pageSize = 5;
        $startLimit = ($page-1) * $pageSize;
        $SQL = 'SELECT cars.id_car, brands.name AS brand_name, models.name AS model_name, cars.price, cars_photos.file_name
                FROM cars
                JOIN models ON cars.id_model = models.id_model
                JOIN brands ON models.id_brand = brands.id_brand
                LEFT JOIN cars_photos ON cars_photos.id_car = cars.id_car AND is_main = 1
                ORDER BY cars.id_car DESC
                LIMIT :start, :limit';
        $cars = Yii::$app->db->createCommand($SQL)->bindValues([':start'=>$startLimit, ':limit'=>$pageSize])->queryAll();
        $count = Yii::$app->db->createCommand('SELECT count(id_car) FROM cars')->queryScalar();
        return $this->render('index', ['cars'=>$cars, 'count'=>$count, 'pageSize'=>$pageSize]);
    }

    public function actionDelete($id_car){
        $SQL = 'SELECT file_name FROM cars_photos WHERE id_car = :id_car';
        $photos = Yii::$app->db->createCommand($SQL)->bindValue(':id_car', $id_car)->queryAll();
        if(!empty($photos)){//удаление фото
            $skip = array('.', '..');
            $carsDir = Cars::getDIR();
            $dirs = scandir(Cars::getDIR());//поиск всех разрешений фото
            foreach ($photos AS $photo){
                foreach($dirs as $dir) {
                    if(!in_array($dir, $skip) && file_exists($carsDir.$dir.'/'.$photo['file_name'])){
                        @unlink($carsDir.$dir.'/'.$photo['file_name']);
                    }
                }
            }
        }
        $count = Yii::$app->db->createCommand()->delete('cars', 'id_car = :id_car')
            ->bindValues([':id_car'=>$id_car])->execute();
        if($count)
            return $this->redirect(['cars/index']);
        else
            throw new NotFoundHttpException('Объявление не найдено.');
    }

    public function actionCar($id_car){
        $SQL = 'SELECT cars.*, brands.name AS brand_name, models.name AS model_name FROM cars 
                JOIN models ON cars.id_model = models.id_model
                JOIN brands ON models.id_brand = brands.id_brand
                WHERE id_car = :id_car';
        $car = Yii::$app->db->createCommand($SQL)->bindValue(':id_car', $id_car)->queryOne();
        if(empty($car))
            throw new NotFoundHttpException('Объявление не найдено.');

        $this->view->title = $car['brand_name'].' '.$car['model_name'];
        $SQL = 'SELECT file_name FROM cars_photos WHERE id_car = :id_car ORDER BY id_photo';
        $photos = Yii::$app->db->createCommand($SQL)->bindValue(':id_car', $id_car)->queryColumn();
        $SQL = 'SELECT optional_equipments.name 
                FROM cars_equipments
                JOIN optional_equipments ON optional_equipments.id_equipment = cars_equipments.id_equipment
                WHERE cars_equipments.id_car = :id_car
                ORDER BY optional_equipments.name';
        $equipments = Yii::$app->db->createCommand($SQL)->bindValue(':id_car', $id_car)->queryColumn();
        return $this->render('car', ['car'=>$car, 'photos'=>$photos, 'equipments'=>$equipments]);
    }

    public function actionAdd()
    {
        $this->view->title = 'Добавление объявления';
        $car = new Cars();
        if ($car->load(Yii::$app->request->post()) && $car->add()) {
            return $this->redirect(['cars/index']);
        }
        return $this->render('add', ['car'=>$car]);
    }

    public function actionModels($id_brand){
        return json_encode(ArrayHelper::map(Cars::getModels($id_brand), 'id_model', 'name'));
    }
}