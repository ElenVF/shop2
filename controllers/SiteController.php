<?php

namespace app\controllers;

use app\models\Category;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Product;
use app\models\ProductCategory;

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
        $items = Product::find()
            ->orderBy(['price' => SORT_ASC])
            ->limit(10)
            ->with('productCategories.category')
            ->all();
        foreach ($items as $item) {
            $item->quantity = $this->getBalanceSession($item);
        }
        return $this->render('index', [
            'items' => $items,

        ]);
    }

    public function actionCategory()
    {
        // Получаем ID категории из запроса
        $selectedCategoryId = Yii::$app->request->get('id');
        // Проверяем, существует ли категория с данным ID
        $selectedCategory = Category::findOne($selectedCategoryId);
        if ($selectedCategoryId && !$selectedCategory) {
            throw new NotFoundHttpException('Категория не найдена.');
        }
        $categories = Category::find()->all();
        $items = Product::find()->all(); 
        foreach ($items as $item) {
            $item->quantity = $this->getBalanceSession($item);
        }
        // Если выбрана категория, фильтруем товары по ней
        if ($selectedCategoryId) {
            $productIds = ProductCategory::find()
                ->select('product_id')
                ->where(['category_id' => $selectedCategoryId])
                ->column(); // Получаем массив ID продуктов

            // Фильтруем товары по их ID
            $items = Product::find()
                ->where(['id' => $productIds])
                ->all();
        }
        return $this->render('category', [
            'items' => $items,
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
            'selectedCategory' => $selectedCategory,

        ]);
    }


    public function actionProduct($id)
    {
        $model = $this->findModel($id); 
        $model->quantity = $this->getBalanceSession($model);
        return $this->render('product', [
            'model' => $model,
        ]);
    }

    public function actionBacket()
    {
        $backet = Yii::$app->session->get('backet', []);
        $backetItems = [];
        $totalAmount = 0;

        foreach ($backet as $id => $item) {
            $product = Product::findOne($id);
            if ($product) {
                $backetItems[] = [
                    'id' => $id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'balance' => $item['balance'], 
                ];
                $totalAmount += $product->price * $item['balance'];
            }
        }

        return $this->render('backet', [
            'backetItems' => $backetItems,
            'totalAmount' => $totalAmount,
        ]);
    }





    public function actionAdd($id)
    {
        $product = Product::findOne($id);

        if ($product === null) {
            throw new NotFoundHttpException("Товар не найден.");
        }

        // Получаем количество из POST-запроса
        $balance = Yii::$app->request->post('balance', 1);

        // Проверяем, достаточно ли товара на складе
        if ($product->balance < $balance) {
            Yii::$app->session->setFlash('error', 'Недостаточно товара на складе.');
            return $this->redirect(['product', 'id' => $id]);
        }

        // Получаем текущую корзину
        $backet = Yii::$app->session->get('backet', []);

        if (isset($backet[$id])) {
            // Увеличиваем количество, если товар уже в корзине
            $backet[$id]['balance'] += $balance;
        } else {
            // Добавляем новый товар с дополнительной информацией
            $backet[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'balance' => $balance
            ];
        }
        Yii::$app->session->set('backet', $backet);
        Yii::$app->session->setFlash('success', 'Товар добавлен в корзину.');

        return $this->redirect(['product', 'id' => $id]);
    }
    public function actionRemove($id)
    {
        $backet = Yii::$app->session->get('backet', []);
    
        // Проверяем, существует ли товар в корзине
        if (isset($backet[$id])) {
            // Удаляем товар из корзины
            unset($backet[$id]);
    
            // Сохраняем обновленную корзину в сессии
            Yii::$app->session->set('backet', $backet);
            Yii::$app->session->setFlash('success', 'Товар удален из корзины.');
        }
    
        // Перенаправляем обратно на страницу
        return $this->redirect(['site/backet']); 
    }
    







    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    protected function getBalanceSession($model)
    {
        // Получаем данные из корзины
        $backet = Yii::$app->session->get('backet', []);
        $balanceBacket = $backet[$model->id]['balance'] ?? 0; // Количество в корзине
        $balanceBd = $model->balance; // Общее количество на складе

        return $balanceBd - $balanceBacket; // Доступное количество
    }




    public function actionUpdateBalance($id)
{
    $backet = Yii::$app->session->get('backet', []);
    $product = Product::findOne($id);
    // Получаем новое количество из POST-запроса
    $newBalance = Yii::$app->request->post('balance', 1);

    // Проверяем, достаточно ли товара на складе
    if ($product->balance < $newBalance) {
        Yii::$app->session->setFlash('error', 'Недостаточно товара на складе.');
        return $this->redirect(['site/backet']);
    }
    // Проверяем, существует ли товар в корзине
    if (isset($backet[$id])) {
        // Обновляем количество товара в корзине
        $backet[$id]['balance'] = $newBalance;
        Yii::$app->session->set('backet', $backet);
        Yii::$app->session->setFlash('success', 'Количество товара обновлено.');
    } else {
        Yii::$app->session->setFlash('error', 'Товар не найден в корзине.');
    }
    return $this->redirect(['site/backet']);
}

}
