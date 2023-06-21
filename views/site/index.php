<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var Authors[] $topAuthors */

$this->title = 'Top 10 Authors';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Top 10 Authors of <?= $year ?></h1>

        <p class="lead">
            <form action="/" method="get">
                Check the top authors of
                <input type="number" id="year" name="year" min="0" max="<?= date('Y') ?>" value="<?= $year ?>">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </p>
    </div>

    <div class="body-content text-center">
        <?php if (count($topAuthors) === 0) : ?>
            No book published in <?= $year ?>
        <?php else : ?>
            <?php foreach ($topAuthors as $author) : ?>
                <div class="row">
                    <div class="col-12">
                        <h2><?= $author->full_name ?></h2>
                        <p>
                            <a class="btn btn-outline-secondary btnSubscribe" href="#" data-author-name="<?= $author->full_name ?>" data-author-id="<?= $author->id ?>">Subscribe</a>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal HTML -->
<div id="subscribeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscribe to <span id="authorName"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Enter your mobile number:</p>
                <input type="text" id="phoneNumber" class="form-control" placeholder="Mobile Number">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSubscribe">Subscribe</button>
            </div>
        </div>
    </div>
</div>
