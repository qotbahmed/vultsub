    <?php

    use yii\db\Migration;

    /**
     * Handles adding trial fields to table `user`.
     */
    class m240915_000000_add_trial_fields_to_user extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->addColumn('user', 'trial_started_at', $this->integer()->null()->comment('Trial start timestamp'));
            $this->addColumn('user', 'trial_expires_at', $this->integer()->null()->comment('Trial expiry timestamp'));

            // Add indexes
            $this->createIndex('idx_user_trial_started_at', 'user', 'trial_started_at');
            $this->createIndex('idx_user_trial_expires_at', 'user', 'trial_expires_at');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->dropIndex('idx_user_academy_id', 'user');
            $this->dropIndex('idx_user_trial_expires_at', 'user');
            $this->dropIndex('idx_user_trial_started_at', 'user');

            $this->dropColumn('user', 'academy_id');
            $this->dropColumn('user', 'trial_expires_at');
            $this->dropColumn('user', 'trial_started_at');
        }
    }
