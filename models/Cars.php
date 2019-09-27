<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


class Cars extends Model
{
    public $id_brand;
    public $id_model;
    public $mileage;
    public $price;
    public $phone;
    public $equipments;
    public $images;

    public function rules()
    {
        return [
            [['id_brand', 'id_model', 'price', 'phone'], 'required', 'message'=>'Заполните поле {attribute}'],
            [['id_brand', 'id_model'], 'integer'],
            [['mileage', 'price', 'phone'], 'string', 'max' => 100],
            ['equipments', 'each', 'rule' => ['in', 'range' => ArrayHelper::getColumn(static::getEquipments(), 'id_equipment')]],
            ['images', 'file',
                'extensions' => 'png, jpg, jpeg',
                'maxFiles' => 3,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Одна или несколько фотографий больше {formattedLimit}',
                'tooSmall'=>'Одна или несколько фотографий меньше {formattedLimit}',
                'tooMany'=>'Вы можете загрузить до {limit} фотографий',
                'wrongExtension'=>'Поддерживаются только файлы с расширениями: {extensions}'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_brand' => 'Марка',
            'id_model' => 'Модель',
            'mileage' => 'Пробег',
            'price' => 'Цена',
            'phone' => 'Телефон',
            'equipments'=> 'Доп. оборудование',
            'images'=>'Фотографии'
        ];
    }

    //полный путь к папке с фотографиями объявлений
    public static function getDIR(){
        return $_SERVER['DOCUMENT_ROOT'].'/content/cars/';
    }

    public static function getBrands(){
        $SQL = 'SELECT * FROM brands ORDER BY name';
        return Yii::$app->db->createCommand($SQL)->queryAll();
    }

    public static function getModels($id_brand = 0){
        $SQL = 'SELECT * FROM models WHERE id_brand = :id_brand ORDER BY name';
        return Yii::$app->db->createCommand($SQL)->bindValue(':id_brand', $id_brand)->queryAll();
    }
    public static function getEquipments(){
        $SQL = 'SELECT * FROM optional_equipments ORDER BY name';
        return Yii::$app->db->createCommand($SQL)->queryAll();
    }

    public function add(){
        if($this->validate()){
            Yii::$app->db->createCommand()->insert('cars', [
                'id_model'=>$this->id_model,
                'mileage'=>$this->mileage,
                'price'=>$this->price,
                'phone'=>$this->phone
            ])->execute();
            $id_car = Yii::$app->db->getLastInsertID();
            if(is_array($this->equipments))
                foreach ($this->equipments AS $equipment){
                    Yii::$app->db->createCommand()->insert('cars_equipments', [
                        'id_car'=>$id_car,
                        'id_equipment'=>$equipment,
                    ])->execute();
                }
            if($this->images = UploadedFile::getInstances($this, 'images')){
                $DIR = static::getDIR();
                foreach ($this->images AS $num=>$image){
                    $name_of_file = strtolower(md5($image->baseName.mktime()).'.'.$image->extension);
                    $i = 0;
                    while (file_exists($DIR.'/original/'.$name_of_file)){//если такой файл уже есть - создать другое название
                        $i++;
                        $name_of_file = strtolower(md5($image->baseName.$i.mktime()).'.'.$image->extension);
                    }
                    $newImage = new \Imagick($image->tempName);
                    $geometry = $newImage->getImageGeometry();
                    $newImage->writeImage($DIR.'/original/'.$name_of_file);
                    $skip = ['.', '..', 'original'];
                    $scan = scandir($DIR);
                    //создаем миниатюры для каждого разрешения
                    foreach($scan as $key=>$resolution) {
                        if(!in_array($resolution, $skip)){
                            $geometryCurrent = $geometry;
                            list($width, $height) = explode('x', $resolution);
                            //если ширина больше необходимой - уменьшаем фото
                            if($geometryCurrent['width'] > $width){
                                $newImage->thumbnailImage($width, null );
                                $geometryCurrent = $newImage->getImageGeometry();
                            }
                            //если высота больше необходимой - уменьшаем фото
                            if($geometryCurrent['height'] > $height){
                                $newImage->thumbnailImage(null, $height );
                                $geometryCurrent = $newImage->getImageGeometry();
                            }


                            //исключаем случай когда разрешение картинки отличиается на 1 пиксель от необходиомого
                            if(($width - $geometryCurrent['width'])%2 > 0){
                                $geometryCurrent['width']--;
                                $newImage->cropImage($geometryCurrent['width'], $geometryCurrent['height'], 1, 0);
                            }
                            if(($height - $geometryCurrent['height'])%2 > 0){
                                $geometryCurrent['height']--;
                                $newImage->cropImage($geometryCurrent['width'], $geometryCurrent['height'], 0, 1);
                            }

                            //получаем толщину бордюров
                            $x = ( $width - $geometryCurrent['width'] ) / 2;
                            $y = ( $height - $geometryCurrent['height'] ) / 2;
                            $newImage->borderImage('white', $x, $y);
                            $newImage->writeImage($DIR.'/'.$resolution.'/'.$name_of_file);

                            $newImage->destroy();
                            $newImage = new \Imagick($image->tempName);
                        }
                    }
                    Yii::$app->db->createCommand()->insert('cars_photos', [
                        'id_car'=>$id_car, 'file_name'=>$name_of_file, 'is_main'=>$num==0 ? 1 : 0
                    ])->execute();
                }

            }
            return $id_car;
        }
        else
            return false;

    }
}
