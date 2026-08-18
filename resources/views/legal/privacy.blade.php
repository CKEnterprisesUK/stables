<x-gallery-layout>
    @section('title', 'Privacy Notice')

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold text-stable-900 mb-6">Privacy Notice</h1>
        <p class="text-sm text-stable-500 mb-8">Last updated: {{ date('j F Y') }}</p>

        <div class="prose prose-stable max-w-none space-y-6 text-stable-700">
            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">1. Who We Are</h2>
                <p>This website is operated by {{ $stableBranding->name ?? config('app.name', 'Horse Sponsorship') }} ("we", "us", "our"). We are committed to protecting your personal information and your right to privacy.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">2. Information We Collect</h2>
                <p>We collect information that you provide directly to us when you:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Create a sponsor account (name, email address, password)</li>
                    <li>Set up a sponsorship (payment information processed securely via Stripe)</li>
                    <li>Contact us or communicate with us</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">3. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Process and manage your horse sponsorship</li>
                    <li>Send you updates about your sponsored horse(s)</li>
                    <li>Process payments securely through Stripe</li>
                    <li>Communicate with you about your account</li>
                    <li>Generate sponsorship certificates</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">4. Payment Processing</h2>
                <p>All payment information is processed securely by <a href="https://stripe.com/privacy" target="_blank" rel="noopener" class="text-brand-600 hover:text-brand-700">Stripe</a>. We do not store your full card details on our servers. Stripe's privacy policy governs their handling of your payment data.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">5. Data Sharing</h2>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties except:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Stripe, for payment processing</li>
                    <li>Email service providers, to send you notifications and updates</li>
                    <li>When required by law or to protect our rights</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">6. Data Retention</h2>
                <p>We retain your personal information for as long as your account is active or as needed to provide you services. If you cancel your sponsorship, we will retain your information for a reasonable period for record-keeping purposes.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">7. Your Rights</h2>
                <p>You have the right to:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Access the personal data we hold about you</li>
                    <li>Request correction of inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Withdraw consent for communications</li>
                    <li>Request a copy of your data in a portable format</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">8. Cookies</h2>
                <p>We use essential cookies to keep you logged in and maintain your session. We do not use advertising or tracking cookies.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">9. Security</h2>
                <p>We implement appropriate technical and organisational measures to protect your personal information against unauthorised access, alteration, disclosure, or destruction.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">10. Changes to This Notice</h2>
                <p>We may update this privacy notice from time to time. We will notify you of any significant changes by posting the new notice on this page.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">11. Contact Us</h2>
                <p>If you have any questions about this privacy notice or our data practices, please contact us through the details provided on our website.</p>
            </section>
        </div>
    </div>
</x-gallery-layout>
