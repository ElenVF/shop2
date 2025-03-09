<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;

$this->params['breadcrumbs'][] = $this->title;
yii\web\YiiAsset::register($this);
?>
<div class="product-view container mt-5">

    <h1 class="display-4 text-center pb-3"><?= Html::encode($this->title) ?></h1>

    <div class="card  mb-4 shadow-sm">
        <div class="row g-0">
            <div class="col-md-6">
                <div class="card-body">
            <div class="text">
    <p class="card-text p-3 text-uppercase border-bottom">Описание:&ensp; <?= Html::encode($model->description) ?></p>
    <p class="card-text p-3 text-uppercase border-bottom">Количество на складе:&ensp; <?= Html::encode($model->quantity) ?> шт.</p>
    <p class="card-text p-3 text-uppercase border-bottom">Цена:&ensp; <?= Html::encode($model->price) ?> руб.</p>
    <p class="card-text p-3 text-uppercase border-bottom">Категории:&ensp;

  
   <?php
   $categoryLinks = array_map(function ($category) {
       $categoryUrl = Url::to(['category', 'id' => $category->category->id]);
       return Html::a(Html::encode($category->category->name), $categoryUrl, [
           'style' => 'color: black; text-decoration: none;'
       ]);
   }, $model->productCategories);
   
   echo implode('  |  ', $categoryLinks);
  
?>

    </p>
</div>

<?php if ($model->quantity >= 1): ?>
    <form class="row g-3 p-3 mt-5" action="<?= Url::to(['site/add', 'id' => $model->id]) ?>" method="post">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <div class="col-auto">
            <div data-mdb-input-init class="form-outline" style="width: 22rem;">
                <input value="1" min="1" type="number" id="typeNumber" name="balance" class="form-control form-control-sm" required />
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-cart-check"></i>&ensp;Добавить в корзину
            </button>
        </div>
    </form>
<?php else: ?>
    <p class="text-danger p-3">Товар временно отсутствует на складе.</p>
<?php endif; ?>

                </div>
            </div>
            <div class="col-md-6">
              
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (empty($model->productImages)): ?>
                            <div class="carousel-item active">
                                <?= Html::img(Url::to('@web/images/no_image.jpg'), [
                                    'class' => 'd-block w-100',
                                    'style' => 'padding:15px 15px 15px 0; object-fit: cover;',
                                    'alt' => 'Нет изображения'
                                ]) ?>
                            </div>
                        <?php else: ?>
                            <?php foreach ($model->productImages as $index => $image): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <?= Html::img(Url::to('@web/' . Html::encode($image->image_path)), [
                                        'class' => 'd-block w-100',
                                        'style' => 'padding:15px 15px 15px 0;width:10em;height:31em; object-fit: cover;',
                                        'alt' => 'Слайд' . ($index + 1)
                                    ]) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>















</div>