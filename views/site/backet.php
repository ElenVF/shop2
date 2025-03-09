<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Корзина';
?>
<div class="container">
    <h1 class="text-center pb-3"><?= Html::encode($this->title) ?></h1>

    <?php if (empty($backetItems)): ?>
        <div class="alert alert-info text-center">
            Ваша корзина пуста.
        </div>
    <?php else: ?>
        <table class="table  table-bordered table_custom ">
            <thead class="table-dark">
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Итого</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backetItems as $item): ?>
                    <tr>
                        <td><?= Html::encode($item['name']) ?></td>
                        <td><?= Html::encode($item['price']) ?> ₽
                        <td> 
                        <form action="<?= yii\helpers\Url::to(['site/update-balance', 'id' => $item['id']]) ?>" method="post">

  <input type="number"  class="form-control-sm "  name="balance" value="<?= Html::encode($item['balance']) ?>" min="1" required>&ensp;
    <button type="submit" class="btn btn-outline-primary btn-sm ">Обновить</button><input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
</form>

                        </td>

                        
                        <td><?= Html::encode($item['price'] * $item['balance']) ?> ₽</td>
                        <td>
                            <?= Html::a('<i class="bi bi-cart-dash-fill"></i>&ensp;Удалить', ['site/remove', 'id' => $item['id']], [
                                'class' => 'btn btn-outline-danger delete-button btn-sm',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="text-right">Общая сумма: <?= Html::encode($totalAmount) ?> ₽</h3>

        <div class="text-right">
            <?= Html::a('Оформить заказ', ['site/backet'], ['class' => 'btn btn-outline-success']) ?>
        </div>









<?php endif; ?>
</div>
