<?php
/**
 * @var yii\web\View $this
 * @var string $content
 */

use backend\models\Settings;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

$this->beginContent('@frontend/views/layouts/_clear.php')
?>

    <!-- Begin page content -->
    
    <?php echo $content ?>

<?php $this->endContent() ?>