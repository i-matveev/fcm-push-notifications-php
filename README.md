# FCM Push Notifications in PHP

This project demonstrates how to send push notifications to devices subscribed to Firebase Cloud Messaging (FCM) topics using the **FCM HTTP v1 API**. It uses the **Google API Client** library for OAuth 2.0 authentication to generate an access token required for FCM API requests.

## Requirements

Before running the script, ensure you have the following installed:

- PHP 7.4+ (CLI)
- Composer
- A Firebase project with Cloud Messaging API enabled
- A service account JSON key file from your Firebase project

### Prerequisites

- **Service Account JSON Key**:
  Generate a service account key from your Firebase project and download the JSON file:
  
  1. Go to the **Firebase Console**.
  2. Navigate to **Project Settings** → **Service Accounts**.
  3. Click **Generate New Private Key** to download the JSON file.

- **Enable FCM API**:
  Ensure the **Firebase Cloud Messaging API** is enabled in the [Google Cloud Console](https://console.cloud.google.com/apis).

## Installation

1. **Install PHP and Composer** (if not already installed):

   ```bash
   sudo apt update
   sudo apt install php-cli php-curl php-zip unzip git
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

2. **Clone the repository** or create a project folder:

   ```bash
   mkdir ~/fcm-push-notifications-php
   cd ~/fcm-push-notifications-php
   ```

3. **Create a `composer.json` file**:

   ```json
   {
       "require": {
           "google/apiclient": "^2.0"
       }
   }
   ```

4. **Install the required dependencies**:

   ```bash
   composer install
   ```

5. **Add your Firebase service account key** (JSON) to the project folder:

   ```bash
   mv /path/to/your-service-account.json ~/fcm-push-notifications-php/
   ```

## Usage

1. **Edit the script**:

   Create a PHP script (e.g., `send_notification.php`) and add the code from this project. Update the `$serviceAccountFile` variable in the script to point to your Firebase JSON file:

   ```php
   $serviceAccountFile = 'path/to/your-service-account.json';
   ```

2. **Run the script**:

   Execute the PHP script through the command line. It will prompt you to input:
   - The target platform (`ios` or `android`)
   - The FCM topic (e.g., `all`, `ios`)
   - The notification title and message (multi-line messages are supported)

   ```bash
   php send_notification.php
   ```

3. **Input details**:
   - **Platform**: Select whether the notification will be sent to iOS or Android.
   - **Topic**: Enter the FCM topic.
   - **Title and Message**: Provide the title and message for the notification. For the message, you can enter multiple lines, ending with "END" to signal the completion of input.

4. **Example Input**:

   ```
   Enter the platform (ios or android): ios
   Enter the topic (e.g., 'all' or 'ios'): ios
   Enter the notification title: New Products!
   Enter the notification message (type 'END' on a new line to finish):
   Good morning, dear customers!
   We invite you to try new products from a new supplier!
   END
   ```

## Notification Delivery

- For **iOS**, both `data` and `notification` payloads are used to ensure the message shows up in the notification shade and can be processed in the app.
- For **Android**, only the `data` payload is used, as Android processes both notifications and data differently.

## License

This project is licensed under the MIT License.

---

### Notes
- Make sure to use the correct service account.
- The service account must have permissions to send messages through Firebase Cloud Messaging.
- Notifications are handled differently on iOS and Android:
  - **iOS**: Requires both `data` and `notification` payloads to show the message in the notification shade and process it within the app.
  - **Android**: Can handle messages with just the `data` payload and show notifications in the shade.