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
use yii\base\Exception;

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
     * @throws Exception if incorrect data
     *
     * @throws Exception if no all arguments
     */
    public function actionPoint()
    {
        $params = Yii::$app->request->get();
        $correctPoints = array();
        if (isset($params['lang']) && isset($params['lat']) && isset($params['radius'])) {
            if (is_numeric($params['lang']) && is_numeric($params['lat']) && is_numeric($params['radius'])) {
                $points = Point::find()->all();
                foreach ($points as $point) {
                    if ($params['radius'] >= Calculate::LatLngDist(array($params['lat'], $params['lang']), array($point->latitude, $point->longitude))) {
                        array_push($correctPoints, array('latitude' => $point->latitude, 'longitude' => $point->longitude));
                    }
                }
                return $this->render('point', [
                    'correctPoints' => $correctPoints,
                    'pointMain' => json_encode(array($params['lat'], $params['lang'])),
                    'json' => json_encode($correctPoints)
                ]);
            } else {
                throw new Exception('Incorrect data');
            }
        } else {
            throw new Exception('No all arguments');
        }
    }

    /**
     * Rand new points.
     *
     * @return string
     */
    public function actionRandom()
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
