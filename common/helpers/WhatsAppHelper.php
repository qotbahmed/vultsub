<?php
namespace common\helpers;

use api\models\GlobalNotificationQueue;
use common\models\WhatsappQueue;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Yii;

class WhatsAppHelper
{
    /**
     * Send WhatsApp message using Twilio API with a template
     *
     * @param string $recipientPhoneNumber The recipient's WhatsApp number in international format
     * @param string $contentSid The content SID of the template message
     * @return string|null The message SID on success, or an error message on failure
     */
    public static function sendWhatsAppMessage($recipientPhoneNumber, $contentSid, $templateParams = []) {
        try {
            if(env('YII_ENV') === 'prod') {
                $account_sid = env('TWILIO_ACCOUNT_SID');
                $auth_token = env('TWILIO_AUTH_TOKEN');
                $twilio = new Client($account_sid, $auth_token);
        
                // Ensure templateParams is a JSON string
                $templateParams = json_encode($templateParams);
    
                $message = $twilio->messages->create(
                    "whatsapp:{$recipientPhoneNumber}", // The recipient's WhatsApp number
                    [
                        'from' => "whatsapp:+966554485011", // Your Twilio WhatsApp number
                        'contentSid' => $contentSid,
                        'contentVariables' => $templateParams
                    ]
                );
            }
           
    
            // Return the Message SID
            return [
                'status' => 'success',
                'message_sid' => $message->sid,
            ];
        } catch (RestException $e) {
            // Return the error message
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

}
