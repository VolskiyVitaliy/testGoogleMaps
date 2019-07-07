<?php

namespace app\controllers;

use app\models\Calculate;
use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Point;
use app\models\PointForm;
use yii\helpers\Json;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Display points.
     *
     * @return string
     *
     */
    public function actionPoint()
    {
        $model = new PointForm();
        $params = Yii::$app->request->get();
        $correctPoints = array();
        if ($model->load($params) && $model->validate()) {
            $points = Point::find()->all();
            foreach ($points as $point) {
                if ($model->radius >= Calculate::LatLngDist(array($model->latitude, $model->longitude), array($point->latitude, $point->longitude))) {
                    array_push($correctPoints, array('latitude' => $point->latitude, 'longitude' => $point->longitude));
                }
            }
            Yii::$app->view->registerJs("var points = " . Json::encode($correctPoints)
                . "; var point = " . Json::encode(array($model->latitude, $model->longitude)) .
                ";",  \yii\web\View::POS_HEAD);
            return $this->render('point-confirm', [
                'correctPoints' => $correctPoints,
//                'pointMain' => json_encode(array($model->latitude, $model->longitude)),
//                'json' => json_encode($correctPoints)
            ]);
        } else {
            return $this->render('point', ['model' => $model]);
        }

    }

    /**
     * Rand new points.
     *
     * @return string
     */
    public
    function actionRandom()
    {
        Point::deleteAll();
        for ($i = 0; $i < 100; $i++) {
            $point = new Point();
            $point->latitude = Calculate::randomFloat(-90, 90);
            $point->longitude = Calculate::randomFloat(-180, 180);
            $point->save();
        }
        return $this->render('random');
    }
}
