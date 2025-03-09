<?php

/** @var yii\web\View $this */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Shop';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Интернет-магазин Shop</h1>
        <p class="lead">Самые необходимые товары для жизни.</p>
        <p><a class="btn btn-lg  btn-outline-secondary" href="/site/category">Открыть каталог</a></p>
    </div>

    <div class="body-content">
        <?php if ($items): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 text-center">
                <?php foreach ($items as $item): ?>
                    <div class="col">

                        <div class="card h-100 shadow-sm border-light"> <!-- Добавляем тень и светлую границу -->
                            <div class="image-container">
                                <?php if (!empty($item->productImages) && isset($item->productImages[0])): ?>
                                    <?= Html::img('@web/' . Html::encode($item->productImages[0]->image_path), [
                                        'class' => 'card-img-top',
                                        'style' => 'height: 250px; object-fit: cover;' // Высота изображения
                                    ]) ?>
                                <?php else: ?>
                                    <?= Html::img('@web/images/no_image.jpg', [
                                        'alt' => 'Default Image',
                                        'class' => 'card-img-top',
                                        'style' => 'height: 250px; object-fit: cover;'
                                    ]); // Путь к изображению по умолчанию 
                                    ?>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= Html::a(Html::encode($item->name), ['product', 'id' => $item->id], ['class' => 'text-dark']) ?></h5>
                                <p class="card-text"><?= Html::encode($item->description) ?></p>

                                <p class="card-text"><strong><?= Html::encode($item->quantity) ?> шт.</strong></p>




                                <p class="card-text">
                                    <?php
                                    $categoryLinks = array_map(function ($category) {
                                        $categoryUrl = Url::to(['category', 'id' => $category->category->id]);
                                        return Html::a(Html::encode($category->category->name), $categoryUrl, [
                                            'style' => 'color: black; text-decoration: none;'
                                        ]);
                                    }, $item->productCategories);
                                    
                                    echo implode('  |  ', $categoryLinks);
                                    ?>
                            

                                </p>
                            </div>
                            <div class="card-footer">
                                <p class="card-text"><strong><?= Html::encode($item->price) ?> ₽</strong></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>