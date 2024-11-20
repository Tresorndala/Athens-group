<?php

class SignupEmailManager {
    private $senderEmail;
    private $counterFile;
    private $emailTemplate;
    
    public function __construct($senderEmail) {
        $this->senderEmail = $senderEmail;
        $this->counterFile = __DIR__ . '/user_counter.txt';
        
        // Create counter file if it doesn't exist
        if (!file_exists($this->counterFile)) {
            file_put_contents($this->counterFile, '1000');
        }
    }
    
    private function getNextId() {
        // Get current counter value
        $currentId = (int)file_get_contents($this->counterFile);
        
        // Increment counter
        $nextId = $currentId + 1;
        
        // Save new counter value
        file_put_contents($this->counterFile, $nextId);
        
        return $nextId;
    }
    
    public function sendWelcomeEmail($userEmail, $userName) {
        $userId = $this->getNextId();
        
        // Email headers
        $headers = array(
            'From' => $this->senderEmail,
            'Reply-To' => $this->senderEmail,
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/html; charset=UTF-8'
        );
        
        // Email subject
        $subject = "Welcome to Our Service!";
        
        // Email body
        $message = "
        <html>
        <body>
            <h2>Welcome to Our Service!</h2>
            <p>Dear {$userName},</p>
            <p>Thank you for signing up! We're excited to have you on board.</p>
            <p><strong>Your unique user ID is: {$userId}</strong></p>
            <p>Please keep this ID for your records. You may need it for future reference.</p>
            <br>
            <p>Best regards,<br>The Team</p>
        </body>
        </html>";
        
        // Send email
        try {
            $success = mail(
                $userEmail,
                $subject,
                $message,
                implode("\r\n", array_map(
                    function ($v, $k) { return "$k: $v"; },
                    $headers,
                    array_keys($headers)
                ))
            );
            
            return [
                'success' => $success,
                'userId' => $userId,
                'error' => null
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'userId' => null,
                'error' => $e->getMessage()
            ];
        }
    }
}

// Example usage
function processNewSignup($userEmail, $userName) {
    $emailManager = new SignupEmailManager('your-email@example.com');
    return $emailManager->sendWelcomeEmail($userEmail, $userName);
}

// How to use the script
/*
$result = processNewSignup('user@example.com', 'John Doe');

if ($result['success']) {
    echo "Welcome email sent! User ID: " . $result['userId'];
} else {
    echo "Error sending email: " . $result['error'];
}
*/
?>
