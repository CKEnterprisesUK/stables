<x-gallery-layout>
    @section('title', 'Terms of Service')

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold text-stable-900 mb-6">Terms of Service</h1>
        <p class="text-sm text-stable-500 mb-8">Last updated: {{ date('j F Y') }}</p>

        <div class="prose prose-stable max-w-none space-y-6 text-stable-700">
            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">1. Introduction</h2>
                <p>These terms of service ("Terms") govern your use of the {{ $stableBranding->name ?? config('app.name', 'Horse Sponsorship') }} website and sponsorship platform ("Service"). By using this Service, you agree to be bound by these Terms.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">2. Sponsorship Agreement</h2>
                <p>When you sign up to sponsor a horse, you agree to:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Pay the monthly sponsorship amount displayed at the time of signup</li>
                    <li>Provide accurate personal and payment information</li>
                    <li>Maintain valid payment details for the duration of your sponsorship</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">3. Payments and Billing</h2>
                <p>Sponsorship payments are processed monthly via Stripe. By providing your payment details, you authorise us to charge the agreed monthly amount. You can cancel your sponsorship at any time through your sponsor portal.</p>
                <p class="mt-2">Payments are non-refundable once processed for the current billing period. If you cancel mid-month, you will retain access to your sponsorship benefits until the end of the paid period.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">4. Cancellation</h2>
                <p>You may cancel your sponsorship at any time through your sponsor portal. Cancellation takes effect at the end of your current billing period. No further payments will be taken after cancellation.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">5. Sponsorship Benefits</h2>
                <p>As a sponsor, you will receive:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>A personalised sponsorship certificate</li>
                    <li>Regular updates about your sponsored horse</li>
                    <li>Access to the sponsor portal</li>
                </ul>
                <p class="mt-2">We reserve the right to modify sponsorship benefits with reasonable notice. Sponsorship does not confer ownership or visiting rights unless specifically arranged.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">6. Account Security</h2>
                <p>You are responsible for maintaining the security of your account credentials. You must notify us immediately if you become aware of any unauthorised use of your account.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">7. Acceptable Use</h2>
                <p>You agree not to:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Use the Service for any unlawful purpose</li>
                    <li>Attempt to gain unauthorised access to any part of the Service</li>
                    <li>Interfere with or disrupt the Service</li>
                    <li>Provide false or misleading information</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">8. Intellectual Property</h2>
                <p>All content on this website, including photographs, text, and graphics, is owned by us or our licensors. You may not reproduce, distribute, or use any content without our prior written consent.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">9. Limitation of Liability</h2>
                <p>The Service is provided "as is" without warranties of any kind. We shall not be liable for any indirect, incidental, or consequential damages arising from your use of the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">10. Changes to Terms</h2>
                <p>We reserve the right to modify these Terms at any time. Continued use of the Service after changes constitutes acceptance of the new Terms. We will make reasonable efforts to notify you of significant changes.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">11. Governing Law</h2>
                <p>These Terms are governed by and construed in accordance with the laws of England and Wales. Any disputes shall be subject to the exclusive jurisdiction of the courts of England and Wales.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-stable-900 mt-8 mb-3">12. Contact</h2>
                <p>If you have any questions about these Terms, please contact us through the details provided on our website.</p>
            </section>
        </div>
    </div>
</x-gallery-layout>
