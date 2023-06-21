<?php

namespace app\controllers;

use app\models\Author;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\HttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'subscribe' => ['post'],
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
        $year = Yii::$app->getRequest()->getQueryParam('year');
        if (!$year || !is_numeric($year)) {
            $year = date('Y');
        }

        $sql = <<<SQL
            SELECT author.id, full_name
            FROM book_author
            LEFT JOIN author ON author_id = author.id
            LEFT JOIN book ON book_id = book.id
            WHERE year = :year
            GROUP BY author_id
            ORDER BY COUNT(book_id) DESC
            LIMIT 10
        SQL;
        $topAuthors = Author::findBySql($sql, [':year' => $year])->all();

        $this->getView()->registerJsFile(
            '@web/js/main.js',
            ['depends' => [\yii\web\JqueryAsset::class]]
        );

        return $this->render('index', ['topAuthors' => $topAuthors, 'year' => $year]);
    }

    public function actionSubscribe()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!$postedData = Yii::$app->request->post()) {
            Yii::$app->response->statusCode = 400;
            return [
                'message' => 'Bad Request',
                'code' => 400,
            ];
        }
        if (!isset($postedData['phoneNumber']) || !isset($postedData['authorId'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'message' => 'Bad Request',
                'code' => 400,
            ];
        }
        if (!$author = Author::findOne($postedData['authorId'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'message' => 'Bad Request',
                'code' => 400,
            ];
        }
        // TODO: do more checks there

        $text = 'Subscription to ' . $author->full_name; // текст сообщения
        $sender = 'INFORM';
        $apikey = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';

        $url = 'https://smspilot.ru/api.php'
            . '?send=' . urlencode($text)
            . '&to=' . urlencode($postedData['phoneNumber'])
            . '&from=' . $sender
            . '&apikey=' . $apikey
            . '&format=json';

        $json = file_get_contents($url);

        $j = json_decode($json);
        if (!isset($j->error)) {
            Yii::$app->response->statusCode = 200;
            return [
                'message' => 'success',
                'code' => 200,
            ];
        } else {
            Yii::$app->response->statusCode = 500;
            return [
                'message' => $j->error->description_ru,
                'code' => 400,
            ];
        }
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
            return $this->redirect('/admin');
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
}
