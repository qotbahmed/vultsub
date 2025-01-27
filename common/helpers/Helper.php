<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html;
use common\models\User;
use common\models\Academies;
use common\models\UserProfile;
use DateTime;
use Exception;

class Helper
{
    public static function daysMapping($reverse = false)
    {
        $days = [
            6 => Yii::t('backend', 'Saturday'),  // السبت
            0 => Yii::t('backend', 'Sunday'),    // الأحد
            1 => Yii::t('backend', 'Monday'),    // الإثنين
            2 => Yii::t('backend', 'Tuesday'),   // الثلاثاء
            3 => Yii::t('backend', 'Wednesday'), // الأربعاء
            4 => Yii::t('backend', 'Thursday'),  // الخميس
            5 => Yii::t('backend', 'Friday'),    // الجمعة
        ];

        if ($reverse) {
            return array_flip($days);  // Reverse the array to map names to integers
        }

        return $days;
    }

    public static function getDaysMapping()
    {
        return [
            'sat' => Yii::t('backend', 'Saturday'),
            'sun' => Yii::t('backend', 'Sunday'),
            'mon' => Yii::t('backend', 'Monday'),
            'tue' => Yii::t('backend', 'Tuesday'),
            'wed' => Yii::t('backend', 'Wednesday'),
            'thu' => Yii::t('backend', 'Thursday'),
            'fri' => Yii::t('backend', 'Friday'),
        ];
    }

    public static function getArabicDayNames(array $days)
    {
        if (empty($days)) {
            return [Yii::t('backend', 'No days selected')];
        }

        $daysMapping = self::getDaysMapping(); // Use the mapping from the getDaysMapping method

        return array_map(function ($day) use ($daysMapping) {
            return $daysMapping[$day] ?? $day;
        }, $days);
    }

    // list user data belonging to academy
    public static function userData($user_type, $select_id, $select_column, $order)
    {
        $controller = Yii::$app->controller;
        $userAcademyId = $controller->academyMainObj->id;

        // Determine academy IDs
        $academy = Academies::findOne($userAcademyId);
        $academyIds = [$userAcademyId];

        if ($academy && $academy->main == 1) {
            $subBranchIds = Academies::find()
                ->select('id')
                ->where(['parent_id' => $userAcademyId])
                ->column();
            $academyIds = array_merge($academyIds, $subBranchIds);
        }

        // Fetch coaches based on filtered academy IDs
        return UserProfile::find()
            ->joinWith('user')
            ->where(['user.user_type' => $user_type])
            ->andWhere(['academy_id' => $academyIds])
            ->select([$select_id, $select_column])
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    // Convert time format to be inserted in database
    public static function formatTime($timeString)
    {
        try {
            $time = DateTime::createFromFormat('h:i A', $timeString);
            return $time ? $time->format('H:i:s') : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function formatTimeForEdit($timeString)
    {
        try {
            // Convert the 24-hour format time to a DateTime object
            $time = DateTime::createFromFormat('H:i:s', $timeString);
            // Return the time in 12-hour format with AM/PM for display in the form
            return $time ? $time->format('h:i A') : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function generateItemHtml($itemNames, $items, $type, $modalTitle) {
        $count = count($items);
        if ($count) {
            // Create a modal that shows the item names
            $modalId = $type . '-modal-' . uniqid(); // Unique modal ID for each row
            $buttonLabel = Yii::t('backend', 'Show All') . " ({$count})"; // Display count with button label
        
            $modalTrigger = Html::a($buttonLabel, '#', [
                'data-toggle' => 'modal',
                'data-target' => "#$modalId",
                'class' => 'btn btn-primary btn-sm', // Optional: Add Bootstrap classes for styling
            ]);
        
            // Modal HTML
            $modalHtml = '<div class="modal fade" id="' . $modalId . '" tabindex="-1" role="dialog" aria-labelledby="' . $type . 'ModalLabel" aria-hidden="true">'
                    . '<div class="modal-dialog" role="document">'
                    . '<div class="modal-content">'
                    . '<div class="modal-header">'
                    . '<h5 class="modal-title" id="' . $type . 'ModalLabel">' . $modalTitle . '</h5>'
                    . '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span>'
                    . '</button>'
                    . '</div>'
                    . '<div class="modal-body">'
                    . implode('<br>', $itemNames)
                    . '</div>'
                    . '<div class="modal-footer">'
                    . '<button type="button" class="btn btn-secondary" data-dismiss="modal">' . Yii::t('backend', 'Close') . '</button>'
                    . '</div>'
                    . '</div>'
                    . '</div>'
                    . '</div>';
        
            return $modalTrigger . $modalHtml;
        } else {
            return 0;
        }
    }
    
    public static function getNames($items, $attributeCallback) {
        return array_map(function($item) use ($attributeCallback) {
            return Html::encode(call_user_func($attributeCallback, $item));
        }, $items);
    }

    public static function fetchItems($modelClass, $relations = [], $conditions = []) {
        // Start building the query from the specified model class
        $query = $modelClass::find();
        
        // Add any relations that need to be joined
        if (!empty($relations)) {
            $query->joinWith($relations);
        }
        
        // Add filtering conditions
        if (!empty($conditions)) {
            $query->where($conditions);
        }
        
        // Fetch the results
        return $query->all();
    }
    
    public static function renderItemList($academyId, $modelClass, $relations, $conditions, $type, $attributeCallback, $modalTitle) {
        // Fetch items
        $items = self::fetchItems($modelClass, $relations, $conditions);        
    
        // Fetch the item names
        $itemNames = self::getNames($items, $attributeCallback);
    
        // Call the generateItemHtml method to generate the modal with the item names
        return self::generateItemHtml($itemNames, $items, $type, $modalTitle);
    }

    //use with repeated fields
    public static function renderUserColumn($model, $userType, $type, $label) {
        $result = Helper::renderItemList(
            $model->id, 
            User::class, 
            ['userProfile'], 
            [
                'user_profile.academy_id' => $model->id,
                'user.user_type' => $userType,
            ], 
            $type, 
            function($user) {
                return $user->userProfile->firstname;
            },
            $label
        );
        return $result ?: Yii::t('backend', 'Unknown');
    }
    
    public static function getNationalities()
    {
        return [
            'AF' => Yii::t('backend', 'Afghanistan'),
            'AL' => Yii::t('backend', 'Albania'),
            'DZ' => Yii::t('backend', 'Algeria'),
            'US' => Yii::t('backend', 'United States'),
            'CA' => Yii::t('backend', 'Canada'),
            'FR' => Yii::t('backend', 'France'),
            'DE' => Yii::t('backend', 'Germany'),
            'EG' => Yii::t('backend', 'Egypt'),
            // Add more countries as needed
        ];
    }

    // public static function searchItems($modelClass, $selectColumns, $filterColumn, $q, $join = null, $type)
    // {
    //     $user_type = $type;
    //     $userAcademyId = Yii::$app->controller->academyMainObj->id;
    //     //$userAcademyId = Yii::$app->controller->academy_id;

    //     $academy = Academies::findOne($userAcademyId);
    //     $academyIds = [$userAcademyId];

    //     if ($academy && $academy->main == 1) {
    //         $subBranchIds = Academies::find()
    //             ->select('id')
    //             ->where(['parent_id' => $userAcademyId])
    //             ->column();
    //         $academyIds = array_merge($academyIds, $subBranchIds);
    //     }

    //     $query = $modelClass::find()
    //         ->where(['user.user_type' => $user_type])
    //         ->andWhere(['academy_id' => $academyIds])
    //         ->andFilterWhere(['like', $filterColumn, $q]);

    //     if ($join) {
    //         $query->joinWith($join);
    //     }

    //     $query = $query->select($selectColumns)
    //         ->orderBy($filterColumn)
    //         ->asArray()
    //         ->all();

    //     return \yii\helpers\Json::encode($query);
    // }
    public static function searchItems($modelClass, $selectColumns, $filterColumn, $q, $join = null, $type)
    {
        $user_type = $type;
        $userAcademyId = Yii::$app->controller->academyMainObj->id;
    
        $academyIds = [$userAcademyId];
    
        // التحقق من وجود الأكاديمية
        $academy = Academies::findOne($userAcademyId);
        if ($academy && $academy->main == 1) {
            $subBranchIds = Academies::find()
                ->select('id')
                ->where(['parent_id' => $userAcademyId])
                ->column();
            $academyIds = array_merge($academyIds, $subBranchIds);
        }
    
        $query = $modelClass::find()
            ->where(['user.user_type' => $user_type])
            ->andWhere(['academy_id' => $academyIds])
            ->andFilterWhere(['like', $filterColumn, $q]);
    
        if ($join) {
            $query->joinWith($join);
        }
    
        $results = $query->select($selectColumns)
            ->orderBy($filterColumn)
            ->asArray()
            ->all();
    
        return $results; // يمكنك إرجاع المصفوفة مباشرة أو تشفيرها بـ JSON حسب الحاجة
    }
    
    // public static function searchItems($modelClass, $selectColumns, $filterColumn, $q, $join = null, $type)
    // {
    //     $user_type = $type;
    //     $userAcademyId = Yii::$app->controller->academy_id;

    //     // Get the current academy information
    //     $academy = Academies::findOne($userAcademyId);
    //     $academyIds = [$userAcademyId];
        
    //     // If the academy is a main academy, get all sub-branch academies
    //     if ($academy && $academy->main == 1) {
    //         $subBranchIds = Academies::find()
    //             ->select('id')
    //             ->where(['parent_id' => $userAcademyId])
    //             ->column();
    //         $academyIds = array_merge($academyIds, $subBranchIds);
    //     }

    //     // Main query for users directly linked to the academy
    //     $query = $modelClass::find()
    //         ->where(['user.user_type' => $user_type])
    //         ->andWhere(['academy_id' => $academyIds])
    //         ->andFilterWhere(['like', $filterColumn, $q]);

    //     if ($join) {
    //         $query->joinWith($join);
    //     }

    //     // Query for users linked via subscription
    //     $subscriptionQuery = (new \yii\db\Query())
    //         ->select($selectColumns)
    //         ->from('user')
    //         ->leftJoin('user_profile', 'user.id = user_profile.user_id')
    //         ->where(['user_profile.academy_id' => null])
    //         ->andFilterWhere(['like', $filterColumn, $q]);

    //     if ($user_type == User::USER_TYPE_PARENT) {
    //         $subscriptionQuery->andWhere([
    //             'user.id' => (new \yii\db\Query())
    //                 ->select('parent_id')
    //                 ->distinct()
    //                 ->from('subscription')
    //                 ->where(['academy_id' => $userAcademyId])
    //         ]);
            
    //         // If the academy is a main academy, include users from sub-branches
    //         if ($academy && $academy->main == 1) {
    //             $subscriptionQuery->orWhere([
    //                 'user.id' => (new \yii\db\Query())
    //                     ->select('parent_id')
    //                     ->distinct()
    //                     ->from('subscription')
    //                     ->where(['academy_id' => $academyIds])
    //             ]);
    //         }
    //     } elseif ($user_type == User::USER_TYPE_PLAYER) {
    //         $subscriptionQuery->andWhere([
    //             'user.parent_id' => (new \yii\db\Query())
    //                 ->select('parent_id')
    //                 ->distinct()
    //                 ->from('subscription')
    //                 ->where(['academy_id' => $userAcademyId])
    //         ]);
            
    //         // If the academy is a main academy, include players from sub-branches
    //         if ($academy && $academy->main == 1) {
    //             $subscriptionQuery->orWhere([
    //                 'user.parent_id' => (new \yii\db\Query())
    //                     ->select('parent_id')
    //                     ->distinct()
    //                     ->from('subscription')
    //                     ->where(['academy_id' => $academyIds])
    //             ]);
    //         }
    //     }

    //     // Combine both queries using UNION
    //     $query = $query->union($subscriptionQuery);

    //     // Fetch results and encode as JSON
    //     $result = $query->select($selectColumns)
    //         ->orderBy($filterColumn)
    //         ->asArray()
    //         ->all();

    //     return \yii\helpers\Json::encode($result);
    // }

}
