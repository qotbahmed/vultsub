<?php
/**
 * @author Eugine Terentev <eugine@terentev.net>
 * @author Victor Gonzalez <victor@vgr.cl>
 * @var yii\web\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@backend/views/layouts/common.php'); ?>
    <div class="box h-100">
        <div class="box-body h-100">
            <?php echo $content ?>
        </div>
    </div>
<?php $this->endContent(); ?>
