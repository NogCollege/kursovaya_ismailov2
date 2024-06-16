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
use app\models\Task;
use app\models\Schedule;
use app\models\Department;
use yii\helpers\ArrayHelper;




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
    public function actionTask()
    {
        $userId = Yii::$app->user->id;

        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['user_id' => $userId])->orderBy(['due_date' => SORT_DESC]),
        ]);

        return $this->render('task', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAssignTask()
    {
        $model = new Task();
        $users = User::find()->all();
        $departments = Department::find()->all();

        $userList = ArrayHelper::map($users, 'id', 'username');
        $departmentList = ArrayHelper::map($departments, 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Задача успешно назначена.');
            return $this->redirect(['task']);
        }

        return $this->render('assign-task', [
            'model' => $model,
            'userList' => $userList,
            'departmentList' => $departmentList,
        ]);
    }

    public function actionLeave()
    {
        // Логика выставления прогулов или больничных
        Yii::$app->session->setFlash('success', 'Прогул успешно выставлен.');
        return $this->redirect(['task']);
    }

    public function actionExtraWork()
    {
        // Логика назначения дополнительной работы
        Yii::$app->session->setFlash('success', 'Дополнительная работа успешно назначена.');
        return $this->redirect(['task']);
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
    public function actionProfile()
    {
        // Получаем текущего авторизованного пользователя, если он есть
        $user = Yii::$app->user->identity;

        // Проверяем, авторизован ли пользователь
        if ($user !== null) {
            // Если пользователь авторизован, получаем его ID
            $userId = $user->id;

            // Получаем все задачи, связанные с текущим пользователем (по полю user_id)
            $tasks = Task::find()->where(['user_id' => $userId])->all();

            // Отображаем представление с задачами пользователя
            return $this->render('profile', [
                'tasks' => $tasks,
                'user' => $user,
            ]);
        } else {
            // Если пользователь не авторизован, можно выполнить другое действие или просто вывести сообщение
            return $this->render('not_authorized');
        }
    }
    public function actionSubmitReview($id)
    {
        $task = $this->findModel($id);

        if ($task->status == 1) {
            $task->status = 2; // Assuming 2 is the status for "under review"
            if ($task->save()) {
                Yii::$app->session->setFlash('success', 'Задача отправлена на проверку.');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось отправить задачу на проверку.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Некорректный статус задачи.');
        }

        return $this->redirect(['profile']);
    }


}
