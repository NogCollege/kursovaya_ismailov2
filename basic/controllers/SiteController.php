<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\User;
use app\models\ContactForm;
use app\models\Person;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;




class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'manager'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['manager'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->role === User::ROLE_ADMIN;
                        },
                    ],
                ],
            ],
        ];
    }
    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->redirect(['login']);
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->role === User::ROLE_ADMIN) {
                return $this->redirect(['admin']);
            }
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
    public function actionAdmin()
    {
        return $this->render('admin', [
        ]);
    }
    public function actionPerson()
    {
        $userId = Yii::$app->user->id;
        $person = $this->findPersonByUserId($userId);

        if ($person === null) {
            $person = new Person();
            $person->user_id = $userId;
        }

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            Yii::$app->session->setFlash('success', 'Profile updated successfully.');
            return $this->redirect(['person']);
        }

        return $this->render('person', [
            'model' => $person,
        ]);
    }

    protected function findPersonByUserId($userId)
    {
        return Person::findOne(['user_id' => $userId]);
    }

    /**
     * Finds the Person model based on its primary key value.
     *
     * @param integer $user_id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id)
    {
        if (($model = Person::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionStart()
    {
        $userId = Yii::$app->user->id;
        $currentShift = WorkShift::find()->where(['user_id' => $userId, 'end_time' => null])->one();

        if ($currentShift) {
            Yii::$app->session->setFlash('error', 'You already have an ongoing shift.');
        } else {
            $shift = new WorkShift();
            $shift->user_id = $userId;
            $shift->start_time = date('Y-m-d H:i:s');
            $shift->save();
            Yii::$app->session->setFlash('success', 'Shift started.');
        }

        return $this->redirect(['person']);
    }

    public function actionStop()
    {
        $userId = Yii::$app->user->id;
        $currentShift = WorkShift::find()->where(['user_id' => $userId, 'end_time' => null])->one();

        if ($currentShift) {
            $currentShift->end_time = date('Y-m-d H:i:s');
            $currentShift->total_time = (strtotime($currentShift->end_time) - strtotime($currentShift->start_time)) / 60; // Общее время в минутах
            $currentShift->save();
            Yii::$app->session->setFlash('success', 'Shift stopped.');
        } else {
            Yii::$app->session->setFlash('error', 'No ongoing shift to stop.');
        }

        return $this->redirect(['index']);
    }



    public function actionIndex()
    {
        return $this->render('index', [
        ]);

        $userId = Yii::$app->user->id;

        $dataProvider = new ActiveDataProvider([
            'query' => Schedule::find()->where(['user_id' => $userId])->orderBy(['start_time' => SORT_DESC]),
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
    public function actionCreate()
    {
        $model = new Schedule();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Schedule::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = Schedule::findOne($id);
        $model->delete();

        return $this->redirect(['index']);
    }


}
