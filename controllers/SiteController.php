<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use app\forms\LoginForm;
use app\forms\ContactForm;

final class SiteController extends \app\base\BaseController
{
	public function beforeAction($event)
	{
		//$this->filterHttps(['index']);

		return parent::beforeAction($event);
	}

	/**
		public function behaviors()
		{
			return [
				'access' => [
					'class' => \yii\filters\AccessControl::className(),
					'only' => ['index', 'smtp', 'testsmtpsettings', 'cron'],
					'rules' => [
						[
							'allow' => true,
							'matchCallback' => function() {
								$id = \Yii::$app->session->get('id');
								$type = \Yii::$app->session->get('type');
								return !empty($id) && $type === \app\models\User::USERTYPE_SUPER_ADMIN;
							},
						],
					],
				],
			];
		}
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

	public function actionIndex()
	{
		$settings = $this->settings(['smtp']);

		return $this->render('index');
	}

	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}
		return $this->render('login', [
			'model' => $model,
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

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

	public function actionAbout()
	{
		return $this->render('about');
	}
}