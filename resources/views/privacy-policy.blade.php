<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - {{ config('app.name', 'IoT Motor Control') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-2">Privacy Policy</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 italic mb-4">
                <strong>Last Updated:</strong> January 2025
            </p>
            <!-- Quick Links -->
            <div class="flex flex-wrap justify-center gap-3 mt-4">
                <a href="https://ampushworks.com/" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-200">
                    Main Website
                </a>
                <a href="https://ampushworks.com/Website/page/19" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-200">
                    About Us
                </a>
                <a href="https://ampushworks.com/Website/page/28" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-200">
                    Contact Us
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg p-6 sm:p-8 md:p-10 space-y-8">
            
            <!-- Introduction -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    1. Introduction
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300 mb-4">
                    Welcome to the IoT Motor Control Application ("we," "our," or "us"), developed and operated by <strong class="text-zinc-900 dark:text-zinc-100">AMPUSHWORKS ENTERPRISES PRIVATE LIMITED</strong>. We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our mobile application (the "App").
                </p>
                <p class="text-zinc-700 dark:text-zinc-300">
                    By using our App, you agree to the collection and use of information in accordance with this policy. If you do not agree with our policies and practices, please do not use our App.
                </p>
            </section>

            <!-- Information We Collect -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    2. Information We Collect
                </h2>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.1 Personal Information</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We collect the following personal information when you create an account or use our services:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Account Information:</strong> Name, email address, phone number, and password</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Address Information:</strong> Street address, city, state, postal code, and country</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Profile Information:</strong> Profile photo (if you choose to upload one)</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.2 Device and SIM Information</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">To provide SMS-based motor control functionality, we collect:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Phone number from your SIM card(s)</li>
                    <li>Carrier name and network operator information</li>
                    <li>Country ISO code</li>
                    <li>SIM slot information (for dual-SIM devices)</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.3 SMS Data</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">Our App requires access to SMS functionality to control IoT motors. We collect:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>SMS messages sent by the App (motor control commands: MOTORON, MOTOROFF, STATUS)</li>
                    <li>SMS messages received by the App (motor status responses containing voltage, current, water level, mode, and clock information)</li>
                    <li>Phone numbers associated with sent and received SMS messages</li>
                    <li>Timestamps of SMS communications</li>
                </ul>
                <p class="text-zinc-700 dark:text-zinc-300 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border-l-4 border-yellow-400">
                    <strong class="text-zinc-900 dark:text-zinc-100">Note:</strong> We only access SMS messages that are directly related to motor control operations. We do not read, store, or transmit any other SMS messages on your device.
                </p>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.4 Motor Operation Data</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We collect and store data related to motor operations, including:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Motor status (ON/OFF)</li>
                    <li>Voltage and current readings</li>
                    <li>Water level measurements</li>
                    <li>Operating mode (AUTO/MANUAL)</li>
                    <li>Clock/timestamp information</li>
                    <li>Runtime data</li>
                    <li>Command history (MOTORON, MOTOROFF, STATUS)</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.5 Device Information</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We collect information about your IoT devices:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Device name and description</li>
                    <li>SMS number associated with each device</li>
                    <li>Last activity timestamp</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.6 Technical Information</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We automatically collect certain technical information, including:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Device model and operating system version</li>
                    <li>App version and usage statistics</li>
                    <li>Firebase Cloud Messaging (FCM) tokens for push notifications</li>
                    <li>Authentication tokens</li>
                    <li>Network information and IP addresses</li>
                    <li>App crash reports and error logs</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">2.7 Analytics Data</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We use Firebase Analytics to collect anonymous usage data, including:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300">
                    <li>App feature usage</li>
                    <li>User interactions</li>
                    <li>Performance metrics</li>
                </ul>
            </section>

            <!-- How We Use Your Information -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    3. How We Use Your Information
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300 mb-3">We use the collected information for the following purposes:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300">
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Service Provision:</strong> To provide, maintain, and improve our motor control services</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Account Management:</strong> To create and manage your user account, authenticate your identity, and process your requests</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">SMS Communication:</strong> To send motor control commands via SMS and receive status updates from your IoT devices</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Data Synchronization:</strong> To synchronize motor operation data between your device and our backend servers</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Notifications:</strong> To send you push notifications about motor status changes, system updates, and important alerts</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Analytics:</strong> To analyze app usage patterns, improve user experience, and develop new features</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Support:</strong> To provide customer support and respond to your inquiries</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Security:</strong> To detect, prevent, and address technical issues, fraud, and security threats</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Compliance:</strong> To comply with legal obligations and enforce our terms of service</li>
                </ul>
            </section>

            <!-- Data Storage and Security -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    4. Data Storage and Security
                </h2>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">4.1 Local Storage</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">Your data is stored locally on your device using Room database, including:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Motor operation logs</li>
                    <li>User account information (passwords are stored securely)</li>
                    <li>Device information</li>
                    <li>Session data and authentication tokens</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">4.2 Cloud Storage</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We synchronize your motor operation data to our secure backend servers to:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Enable data backup and recovery</li>
                    <li>Provide access across multiple devices</li>
                    <li>Generate reports and analytics</li>
                    <li>Maintain historical records</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">4.3 Security Measures</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We implement industry-standard security measures to protect your information:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Encryption of data in transit using HTTPS/TLS</li>
                    <li>Secure authentication using tokens</li>
                    <li>Regular security audits and updates</li>
                    <li>Access controls and authentication requirements</li>
                    <li>Secure password storage practices</li>
                </ul>
                <p class="text-zinc-700 dark:text-zinc-300">
                    However, no method of transmission over the internet or electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your information, we cannot guarantee absolute security.
                </p>
            </section>

            <!-- Data Sharing and Disclosure -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    5. Data Sharing and Disclosure
                </h2>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">5.1 Third-Party Services</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-2">We use the following third-party services that may have access to certain information:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Google Firebase:</strong> We use Firebase Cloud Messaging for push notifications and Firebase Analytics for app analytics. Firebase's privacy policy can be found at <a href="https://firebase.google.com/support/privacy" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">https://firebase.google.com/support/privacy</a></li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Backend API Services:</strong> Your motor operation data is synchronized with our backend servers for data management and reporting</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Webhook Services:</strong> We may send motor command events to webhook endpoints for monitoring and integration purposes</li>
                </ul>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">5.2 Legal Requirements</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-4">We may disclose your information if required by law or in response to valid requests by public authorities (e.g., court orders, government agencies).</p>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">5.3 Business Transfers</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-4">In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of that transaction. We will notify you of any such change in ownership or control.</p>

                <h3 class="text-xl font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-3">5.4 No Sale of Personal Data</h3>
                <p class="text-zinc-700 dark:text-zinc-300">We do not sell, rent, or trade your personal information to third parties for their marketing purposes.</p>
            </section>

            <!-- Permissions Required -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    6. Permissions Required
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300 mb-3">Our App requires the following permissions to function properly:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li><strong class="text-zinc-900 dark:text-zinc-100">SMS Permissions:</strong> SEND_SMS, RECEIVE_SMS, READ_SMS - Required to send motor control commands and receive status updates</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Phone Permissions:</strong> READ_PHONE_STATE, READ_PHONE_NUMBERS - Required to detect your phone number for device identification</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Internet Permission:</strong> INTERNET - Required to synchronize data with backend servers</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Notification Permission:</strong> POST_NOTIFICATIONS - Required to send push notifications about motor status</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Background Service Permissions:</strong> FOREGROUND_SERVICE, WAKE_LOCK - Required for background data synchronization</li>
                </ul>
                <p class="text-zinc-700 dark:text-zinc-300">You can revoke these permissions at any time through your device settings, but this may limit or disable certain features of the App.</p>
            </section>

            <!-- Data Retention -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    7. Data Retention
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300 mb-3">We retain your information for as long as necessary to provide our services and fulfill the purposes described in this Privacy Policy:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Account Information:</strong> Retained while your account is active and for a reasonable period after account deletion for legal and business purposes</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Motor Operation Logs:</strong> Retained for up to 2 years to provide historical data and generate reports</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Analytics Data:</strong> Retained in aggregated and anonymized form</li>
                </ul>
                <p class="text-zinc-700 dark:text-zinc-300 mb-3">You can delete your account and all associated data at any time by visiting our <a href="{{ route('customer.account.delete.form') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Account Deletion Page</a> or by contacting us using the information provided in the "Contact Us" section.</p>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border-l-4 border-blue-500 mt-4">
                    <p class="text-sm text-zinc-700 dark:text-zinc-300">
                        <strong class="text-zinc-900 dark:text-zinc-100">Quick Access:</strong> To delete your account immediately, visit: 
                        <a href="https://laravel1.wizzyweb.com/customer/delete-account" class="text-blue-600 dark:text-blue-400 hover:underline font-medium break-all">https://laravel1.wizzyweb.com/customer/delete-account</a>
                    </p>
                </div>
            </section>

            <!-- Your Rights and Choices -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    8. Your Rights and Choices
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300 mb-3">You have the following rights regarding your personal information:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Access:</strong> Request access to your personal information</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Correction:</strong> Request correction of inaccurate or incomplete information</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Deletion:</strong> Request deletion of your personal information</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Data Portability:</strong> Request a copy of your data in a portable format</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Opt-Out:</strong> Opt-out of certain data collection practices (e.g., analytics)</li>
                    <li><strong class="text-zinc-900 dark:text-zinc-100">Account Deletion:</strong> Delete your account and associated data at any time through our <a href="{{ route('customer.account.delete.form') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Account Deletion Page</a></li>
                </ul>
                <p class="text-zinc-700 dark:text-zinc-300 mb-4">To exercise these rights, you can:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Visit our <a href="{{ route('customer.account.delete.form') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Account Deletion Page</a> to delete your account</li>
                    <li>Contact us using the information provided in the "Contact Us" section for other requests</li>
                </ul>
            </section>

            <!-- Children's Privacy -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    9. Children's Privacy
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300">
                    Our App is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us immediately. If we become aware that we have collected personal information from a child under 13, we will take steps to delete such information.
                </p>
            </section>

            <!-- International Data Transfers -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    10. International Data Transfers
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300">
                    Your information may be transferred to and processed in countries other than your country of residence. These countries may have data protection laws that differ from those in your country. By using our App, you consent to the transfer of your information to these countries.
                </p>
            </section>

            <!-- Changes to This Privacy Policy -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    11. Changes to This Privacy Policy
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300 mb-3">We may update this Privacy Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify you of any material changes by:</p>
                <ul class="list-disc pl-6 space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                    <li>Posting the new Privacy Policy in the App</li>
                    <li>Updating the "Last Updated" date at the top of this policy</li>
                    <li>Sending you a notification (if you have enabled notifications)</li>
                </ul>
                <p class="text-zinc-700 dark:text-zinc-300">We encourage you to review this Privacy Policy periodically to stay informed about how we protect your information.</p>
            </section>

            <!-- Your Consent -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    12. Your Consent
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300">
                    By using our App, you consent to our Privacy Policy and agree to its terms. If you do not agree with this policy, please do not use our App.
                </p>
            </section>

            <!-- Contact Us -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    13. Contact Us
                </h2>
                <div class="bg-zinc-100 dark:bg-zinc-700/50 p-6 rounded-lg">
                    <p class="text-zinc-700 dark:text-zinc-300 mb-4">If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>
                    <div class="space-y-2 text-zinc-700 dark:text-zinc-300 mb-4">
                        <p><strong class="text-zinc-900 dark:text-zinc-100">Company:</strong> AMPUSHWORKS ENTERPRISES PRIVATE LIMITED</p>
                        <p><strong class="text-zinc-900 dark:text-zinc-100">Email:</strong> <a href="mailto:info@AmpushworksEnterprisesPvt.Ltd.in" class="text-blue-600 dark:text-blue-400 hover:underline">info@AmpushworksEnterprisesPvt.Ltd.in</a></p>
                        <p><strong class="text-zinc-900 dark:text-zinc-100">Phone:</strong> <a href="tel:+919470213937" class="text-blue-600 dark:text-blue-400 hover:underline">+91 9470213937</a></p>
                        <p><strong class="text-zinc-900 dark:text-zinc-100">Address:</strong> Priyadarshi Nagar, Bhagwat Nagar, Bahadurpur, B.H.Colony, Sampatchak, Patna- 800026, Bihar, India</p>
                    </div>
                    <div class="border-t border-zinc-300 dark:border-zinc-600 pt-4 mt-4">
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-3">Quick Links:</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://ampushworks.com/" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-200">
                                Main Website
                            </a>
                            <a href="https://ampushworks.com/Website/page/19" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-200">
                                About Us
                            </a>
                            <a href="https://ampushworks.com/Website/page/28" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-200">
                                Contact Us
                            </a>
                        </div>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-3">
                            <strong>Website:</strong> <a href="https://ampushworks.com/" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">https://ampushworks.com/</a>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Additional Information -->
            <section>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 pb-2 border-b-2 border-blue-500">
                    14. Additional Information
                </h2>
                <p class="text-zinc-700 dark:text-zinc-300">
                    This Privacy Policy is effective as of the date stated above and applies to all users of the IoT Motor Control Application. For users in the European Economic Area (EEA), this policy also complies with the General Data Protection Regulation (GDPR). For users in California, this policy also complies with the California Consumer Privacy Act (CCPA).
                </p>
            </section>

            <!-- Account Deletion Section -->
            <section class="mt-8 p-6 bg-red-50 dark:bg-red-900/20 rounded-lg border-l-4 border-red-500">
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Delete Your Account</h3>
                <p class="text-zinc-700 dark:text-zinc-300 mb-4">
                    If you wish to delete your account and all associated data, you can do so at any time. This action is permanent and cannot be undone.
                </p>
                <a href="https://laravel1.wizzyweb.com/customer/delete-account" class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200 font-medium">
                    Delete My Account
                </a>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-3">
                    Account Deletion URL: <a href="https://laravel1.wizzyweb.com/customer/delete-account" class="text-blue-600 dark:text-blue-400 hover:underline break-all">https://laravel1.wizzyweb.com/customer/delete-account</a>
                </p>
            </section>

            <!-- Footer -->
            <hr class="my-8 border-zinc-300 dark:border-zinc-700">
            <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">
                © 2025 AMPUSHWORKS ENTERPRISES PRIVATE LIMITED. All rights reserved.
            </p>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200">
                    Back to Login
                </a>
                <a href="https://ampushworks.com/" target="_blank" class="inline-block px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors duration-200">
                    Visit Main Website
                </a>
                <a href="https://ampushworks.com/Website/page/28" target="_blank" class="inline-block px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors duration-200">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</body>
</html>

