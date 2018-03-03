<?php
use yii\easyii\modules\news\api\News;
use yii\easyii\modules\page\api\Page;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$page = Page::get('page-news');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;
?>
<?php
    $picker = 5;
    $arr = [
        '{picker}' => '<div class="col-md-4">'.$picker.'</div>',
        '{remove}' => '<div class="col-md-4">Remove</div>',
        '{input}' => '<div class="col-md-4">Input</div>',
    ];
    $out = strtr('{picker}{remove}{input}', $arr);
    echo $this->render('template', ['out' => $out, 'picker' => $arr['{picker}']]);
?>
<h1><?= $page->seo('h1', $page->title) ?></h1>
<br/>
<?php foreach($news as $item) : ?>
    <div class="row">
        <div class="col-md-2">
            <?= Html::img($item->thumb(160, 120)) ?>
        </div>
        <div class="col-md-10">
            <?= Html::a($item->title, ['news/view', 'slug' => $item->slug]) ?>
            <div class="small-muted"><?= $item->date ?></div>
            <p><?= $item->short ?></p>
            <p>
                <?php foreach($item->tags as $tag) : ?>
                    <a href="<?= Url::to(['/news', 'tag' => $tag]) ?>" class="label label-info"><?= $tag ?></a>
                <?php endforeach; ?>
            </p>
        </div>
    </div>
    <br>
<?php endforeach; ?>

<?= News::pages() ?>
