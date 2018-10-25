<?php

namespace app\controllers;

use app\models\Object;
use app\models\ObjectLabel;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class ObjectController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        $object = Object::find()->where(['id' => $id, 'visible' => 1])->one();
        if (empty($object)) {
            throw new HttpException(405);
        }
        return $this->render('view', [
            'object' => $object,
        ]);
    }

    public function actionTest($id)
    {
        $object = Object::find()->where(['id' => $id, 'visible' => 1])->one();
        if (empty($object)) {
            throw new HttpException(405);
        }
        return $this->render('test', [
            'object' => $object,
        ]);
    }

    public function actionIframe($id)
    {
        $arr = explode('-', $id);
        $id = $arr[0];
        $color = isset($arr[1]) ? $arr[1] : false;
        $object = Object::find()->where(['id' => $id, 'visible' => 1])->one();

        if (empty($object)) {
            throw new HttpException(405);
        }

        $this->layout = false;

        return $this->render('iframe', [
            'object' => $object,
        ]);
    }

    public function actionData()
    {
        $id = (int)Yii::$app->request->post('id');
        $object = Object::findOne($id) ? Object::findOne($id)->toArray() : null;
        if ($object) {
            $object['label'] = ObjectLabel::find()->where(['object_id' => $id])->asArray()->all();
            $object['option'] = json_decode($object['option']);
            $object['setting'] = json_decode($object['setting']);
        }
        return json_encode($object);
    }

    public function actionSetting()
    {
        $setting['pathImage'] = Object::PATH_IMAGE;
        $setting['pathFile'] = Object::PATH_FILE;

        return json_encode($setting);
    }

    public function actionEdit($id)
    {
        if ($id) {
            return $this->redirect(['/admin/edit-object-general/' . $id]);
        }else{
            return $this->goBack();
        }
    }
}
