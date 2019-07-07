<?php

/* @var $this yii\web\View */
/* @var $model app\models\Point */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('js/point.js');
?>
<div>
    <table class="table">
        <thead>
        <th>latitude</th>
        <th>longitude</th>
        </thead>
        <?php foreach ($correctPoints as $point): ?>
            <tr>
                <td><?= Html::encode($point['latitude']) ?></td>
                <td><?= Html::encode($point['longitude']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div id="map">

</div>

