<?php

use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\SubscriptionForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('common', 'Create Subscription');
$this->params['breadcrumbs'][] = $this->title;

if ($model->close === 1)
    $this->registerJs("$(function() {
            parent.$.fancybox.close();
            parent.location.reload();
        });
    ");
?>


<div class="container">
    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-element-3"></span>
            </span>
            <h4 class="mb-0">
                <?= $this->title ?>
            </h4>
        </div>
        <div class="mb-0 d-inline-flex align-items-center gap-2">

            <!--            --><?php //= Html::a(
            //                Html::tag('i', '', ['class' => 'isax isax-arrow-right-1']) . ' ' . Yii::t('common', 'Back to profile'),
            //                ['index'],
            //                ['class' => 'btn btn-light']
            //            ) ?>
        </div>
    </div>
    <div class="user-create">

        <div class="user-form">

            <?php $form = ActiveForm::begin([
                'id' => 'subscription-form',

            ]); ?>

            <div class="card">
                <div class="card-body d-flex flex-wrap">

                    <?= $form->errorSummary($model); ?>

                    <!-- Player ID field -->
                    <div class="col-md-12 mb-4">

                        <?= $form->field($model, 'player_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                \common\models\User::find()
                                    ->joinWith('userProfile') // Assuming you have a relation in User model
                                    ->where(['user.user_type' => \common\models\User::USER_TYPE_PLAYER])
                                    ->andWhere(['user.parent_id' => Yii::$app->user->id]) // Filter by logged-in user's children
                                    ->orderBy('user.id')
                                    ->asArray()
                                    ->all(),
                                'id',
                                'userProfile.firstname'
                            ),
                            'options' => [
                                'placeholder' => Yii::t('common', 'Select child'),
                                'name' => 'SubscriptionForm[player_id]',
                            ],
                            'pluginOptions' => ['allowClear' => true],
                        ]); ?>
                    </div>

                    <!-- Select Sport -->
                    <div class="col-md-12 mb-4">
                        <label for="selectedActivityControl" class="form-label">اختر النشاط </label>
                        <?php
                        $sports = \common\models\Sport::find()
                            ->innerJoin('academy_sport', 'academy_sport.sport_id = sport.id')
                            ->innerJoin('package', 'package.sport_id = sport.id')
                            ->where(['academy_sport.academy_id' => Yii::$app->user->identity->userProfile->academy_id])
                            ->andWhere(new \yii\db\Expression('EXISTS (
                                        SELECT 1
                                        FROM package p2
                                        WHERE p2.sport_id = sport.id
                                          AND p2.academy_id = academy_sport.academy_id
                                    )'))
                            ->orderBy('sport.id')
                            ->asArray()
                            ->all();

                        echo Select2::widget([
                            'name' => 'SubscriptionForm[sport_id]', // Change to array notation
                            'data' => ArrayHelper::map($sports, 'id', 'title'),
                            'options' => [
                                'class' => 'bg-white',
                                'placeholder' => 'اختر النشاط',
                                'id' => 'selectedActivityControl',
                                'required' => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'containerCssClass' => 'bg-white form-control',
                            ],
                        ]);
                        ?>
                    </div>

                    <!-- Select Package -->
                    <div class="col-md-12 mb-4">
                        <label for="selectedPackageControl" class="form-label">اختر البــاقة </label>
                        <div class="position-relative">

                            <?= Select2::widget([
                                'name' => 'SubscriptionForm[packages_id]', // Change to array notation
                                'data' => [],
                                'options' => [
                                    'class' => 'bg-white',
                                    'placeholder' => 'اختر الباقة',
                                    'id' => 'selectedPackageControl',
                                    'required' => true,
                                    'disabled' => true, // Initially disabled until sport is selected
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'containerCssClass' => 'bg-white form-control'
                                ],
                            ]); ?>
                        </div>
                    </div>

                    <!-- Start Date field -->
                    <div class="col-md-12 mb-4">
                        <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('common', 'Start Date'),
                                'name' => 'SubscriptionForm[start_date]'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                            ],
                        ]); ?>
                    </div>
                    <?= Html::hiddenInput('SubscriptionForm[subscription_id]', $model->subscription_id) ?>
                </div>

                <!-- Submit Button -->
                <div class="card-footer">
                    <?= Html::submitButton(Yii::t('common', 'Create'), ['class' => 'btn btn-primary',
                        'name' => 'create-subscription',
                    ]) ?>
                    <?= Html::submitButton(Yii::t('common', 'Add another child/sport'), [
                        'class' => 'btn btn-primary',
                        'name' => 'add-another-child',
                        'value' => '1',

                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        console.log("jQuery version:", $.fn.jquery);
        var $activityControl = $('#selectedActivityControl');

        $activityControl.on('change', function () {
            var sportId = $(this).val();
            console.log("Selected Sport ID:", sportId);

            if (sportId) {
                $('#packageLoader').show(); // Show the loader

                $.ajax({
                    url: '<?= Url::to(['get-packages']) ?>',  // Correct URL
                    type: 'POST',
                    data: {
                        sportId: sportId,
                        academy_id: <?=Yii::$app->user->identity->userProfile->academy_id?>
                    },  // Send sportId as data
                    success: function (response) {
                        console.log("Ajax request successful, response:", response);
                        var packageData = response.packages || [];
                        var $packageControl = $('#selectedPackageControl');

                        $packageControl.empty().append('<option></option>');

                        if (packageData.length > 0) {
                            $.each(packageData, function (index, package) {
                                $packageControl.append(new Option(package.name, package.id));
                            });
                            $packageControl.prop('disabled', false);
                        } else {
                            $packageControl.prop('disabled', true);
                        }
                        $('#packageLoader').hide();
                    },
                    error: function (xhr, status, error) {
                        console.error("Ajax error:", error);
                        alert('Error: ' + error);
                        $('#packageLoader').hide(); // Hide the loader
                        //Swal.fire({
                        //    title: '<?php //= yii::t('common', 'Error has been occurred') ?>//',
                        //    text: '<?php //= yii::t('common', 'Failed to load packages') ?>//',
                        //    icon: 'error',
                        //    confirmButtonText: '<?php //= yii::t('common', 'OK') ?>//'
                        //});
                    }
                });
            } else {
                $('#selectedPackageControl').empty().prop('disabled', true);
                console.log("Sport ID not selected, disabling package control.");
            }
        });
    });
</script>
