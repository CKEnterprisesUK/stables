@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Help & Guide</h1>
        <p class="mt-1 text-sm text-stable-500">Everything you need to know about managing your stable's sponsorship platform.</p>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <a href="#horses" class="flex items-center gap-3 p-4 bg-white border border-stable-200 rounded-xl shadow-sm hover:border-brand-300 hover:shadow transition-all">
            <div class="h-10 w-10 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-stable-900">Managing Horses</p>
                <p class="text-xs text-stable-500">Add, edit, and update horse profiles</p>
            </div>
        </a>

        <a href="#sponsorships" class="flex items-center gap-3 p-4 bg-white border border-stable-200 rounded-xl shadow-sm hover:border-brand-300 hover:shadow transition-all">
            <div class="h-10 w-10 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-stable-900">Sponsorships</p>
                <p class="text-xs text-stable-500">How sponsorships and payments work</p>
            </div>
        </a>

        <a href="#updates" class="flex items-center gap-3 p-4 bg-white border border-stable-200 rounded-xl shadow-sm hover:border-brand-300 hover:shadow transition-all">
            <div class="h-10 w-10 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-stable-900">Horse Updates</p>
                <p class="text-xs text-stable-500">Post updates and notify sponsors</p>
            </div>
        </a>

        <a href="#gift-cards" class="flex items-center gap-3 p-4 bg-white border border-stable-200 rounded-xl shadow-sm hover:border-brand-300 hover:shadow transition-all">
            <div class="h-10 w-10 rounded-lg bg-saddle-50 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-saddle-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-stable-900">Gift Cards</p>
                <p class="text-xs text-stable-500">Manage gift sponsorship cards</p>
            </div>
        </a>

        <a href="#settings" class="flex items-center gap-3 p-4 bg-white border border-stable-200 rounded-xl shadow-sm hover:border-brand-300 hover:shadow transition-all">
            <div class="h-10 w-10 rounded-lg bg-saddle-50 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-saddle-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-stable-900">Settings</p>
                <p class="text-xs text-stable-500">Branding, email, payments & more</p>
            </div>
        </a>

        <a href="#posters" class="flex items-center gap-3 p-4 bg-white border border-stable-200 rounded-xl shadow-sm hover:border-brand-300 hover:shadow transition-all">
            <div class="h-10 w-10 rounded-lg bg-saddle-50 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-saddle-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-stable-900">Posters & Downloads</p>
                <p class="text-xs text-stable-500">Generate sponsorship posters</p>
            </div>
        </a>
    </div>

    <!-- Managing Horses -->
    <div id="horses" class="mb-8 scroll-mt-6">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Managing Horses</h2>
            </div>
            <div class="px-6 py-5 space-y-4 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Adding a New Horse</h3>
                    <ol class="list-decimal list-inside space-y-1 text-stable-600">
                        <li>Go to <strong>Horses</strong> in the sidebar navigation.</li>
                        <li>Click the <strong>Add Horse</strong> button in the top right.</li>
                        <li>Fill in the horse's name and any details about them.</li>
                        <li>Upload one or more photos &mdash; the first photo will be used as their main profile image.</li>
                        <li>Click <strong>Save</strong> to create the horse profile.</li>
                    </ol>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Editing a Horse</h3>
                    <p class="text-stable-600">Click <strong>View</strong> next to any horse from the Horses list, then use the <strong>Edit</strong> button to update their name, description, or photos. You can reorder photos by dragging them, and remove photos you no longer want.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Deleting a Horse</h3>
                    <p class="text-stable-600">From the Horses list, click <strong>Delete</strong> next to the horse you want to remove. You'll be asked to confirm. <span class="text-red-600 font-medium">Warning:</span> Deleting a horse will also remove all their photos and update history. Active sponsorships should be cancelled first.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsorships -->
    <div id="sponsorships" class="mb-8 scroll-mt-6">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Sponsorships</h2>
            </div>
            <div class="px-6 py-5 space-y-4 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">How Sponsorships Work</h3>
                    <p class="text-stable-600">Sponsors sign up through the public gallery page, choose a horse, and set up a recurring monthly payment via Stripe. Once payment is confirmed, they gain access to their sponsor dashboard where they can view updates, download certificates, and manage their sponsorship.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Sponsorship Statuses</h3>
                    <ul class="space-y-1 text-stable-600">
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span> &mdash; Payment is current and the sponsorship is running.</li>
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Past Due</span> &mdash; A payment has failed. Stripe will retry automatically.</li>
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Cancelled</span> &mdash; The sponsorship has been ended (by the sponsor or admin).</li>
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-stable-100 text-stable-800">Expired</span> &mdash; A gift sponsorship that has reached its end date.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Viewing Sponsors</h3>
                    <p class="text-stable-600">Go to <strong>Sponsors</strong> in the sidebar to see all registered sponsors. Click any sponsor to see their active and past sponsorships, payment history, and contact details.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Cancelling a Sponsorship</h3>
                    <p class="text-stable-600">From a sponsor's detail page, find the sponsorship you want to cancel and click <strong>Cancel Sponsorship</strong>. This will stop future payments and end the sponsor's access at the end of their current billing period.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Horse Updates -->
    <div id="updates" class="mb-8 scroll-mt-6">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Horse Updates</h2>
            </div>
            <div class="px-6 py-5 space-y-4 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">What Are Horse Updates?</h3>
                    <p class="text-stable-600">Updates are posts about a specific horse that get shared with their sponsors. Think of them like a newsletter &mdash; you can write about how the horse is doing, share new photos, and keep sponsors connected to the horse they support.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Posting an Update</h3>
                    <ol class="list-decimal list-inside space-y-1 text-stable-600">
                        <li>Go to <strong>Horses</strong> and click <strong>View</strong> on the horse you want to update.</li>
                        <li>Click <strong>Post Update</strong> on the horse's detail page.</li>
                        <li>Write your update text and optionally attach photos.</li>
                        <li>Click <strong>Save</strong> to publish the update.</li>
                    </ol>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Notifying Sponsors</h3>
                    <p class="text-stable-600">After posting an update, you'll see a <strong>Notify Sponsors</strong> button. Clicking this sends an email to all active sponsors of that horse letting them know there's a new update. Sponsors can then log in to read the full update and view photos.</p>
                    <p class="mt-1 text-stable-500 text-xs italic">Note: You can post an update without notifying sponsors if you prefer &mdash; the notify step is optional.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gift Cards -->
    <div id="gift-cards" class="mb-8 scroll-mt-6">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Gift Cards</h2>
            </div>
            <div class="px-6 py-5 space-y-4 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">How Gift Sponsorships Work</h3>
                    <p class="text-stable-600">Gift cards allow someone to purchase a sponsorship as a gift for another person. The buyer pays upfront for a set period (e.g., 3, 6, or 12 months), and the recipient receives a code they can redeem to activate their sponsorship without needing to set up a payment method.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Managing Gift Cards</h3>
                    <p class="text-stable-600">From the <strong>Gift Cards</strong> section, you can view all purchased gift sponsorships, see their status (purchased, redeemed, or expired), and resend the gift email to the recipient if needed.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Gift Card Statuses</h3>
                    <ul class="space-y-1 text-stable-600">
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Purchased</span> &mdash; Paid for but not yet redeemed by the recipient.</li>
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Redeemed</span> &mdash; The recipient has activated their sponsorship.</li>
                        <li><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-stable-100 text-stable-800">Expired</span> &mdash; The gift was never redeemed and has expired.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Posters & Downloads -->
    <div id="posters" class="mb-8 scroll-mt-6">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Posters & Downloads</h2>
            </div>
            <div class="px-6 py-5 space-y-4 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Sponsorship Posters</h3>
                    <p class="text-stable-600">You can generate printable sponsorship posters to display at your stable or share online. These help promote your sponsorship programme and make it easy for visitors to learn how to sponsor a horse.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Generating a Poster</h3>
                    <ul class="space-y-1 text-stable-600">
                        <li><strong>Generic poster:</strong> From the Horses list, click <strong>Sponsorship Poster</strong> to generate a general poster for your programme.</li>
                        <li><strong>Horse-specific poster:</strong> From a horse's detail page, click the poster option to generate one featuring that specific horse.</li>
                    </ul>
                    <p class="mt-1 text-stable-500 text-xs italic">Tip: Posters include a QR code that links directly to your sponsorship gallery page.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings (Super Admin) -->
    <div id="settings" class="mb-8 scroll-mt-6">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Settings</h2>
                <p class="text-xs text-stable-500 mt-0.5">Settings are only accessible to Super Admins.</p>
            </div>
            <div class="px-6 py-5 space-y-4 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Pricing</h3>
                    <p class="text-stable-600">Configure your monthly sponsorship price and any gift sponsorship pricing tiers. Changes to pricing only affect new sponsorships &mdash; existing sponsors keep their current rate.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Branding</h3>
                    <p class="text-stable-600">Upload your stable's logo, set your stable name, and customise the appearance of your public-facing pages. Your logo appears in the navigation, on emails sent to sponsors, and on generated posters.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Email (SMTP)</h3>
                    <p class="text-stable-600">Configure your outgoing email settings so the system can send notifications, welcome emails, and update alerts to sponsors. You can send a test email to verify everything is working correctly.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Payments (Stripe)</h3>
                    <p class="text-stable-600">Connect your Stripe account to accept sponsorship payments. Once connected, all payments are processed through your Stripe account and funds are deposited directly. You can access your Stripe dashboard to view transactions, manage refunds, and view financial reports.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Admin Users</h3>
                    <p class="text-stable-600">Manage who has access to the admin panel. There are three roles:</p>
                    <ul class="mt-1 space-y-1 text-stable-600">
                        <li><strong>Super Admin</strong> &mdash; Full access to everything including settings and admin user management.</li>
                        <li><strong>Sponsorship Admin</strong> &mdash; Can manage horses, sponsors, gift cards, and view financial info. Cannot access settings.</li>
                        <li><strong>Update Admin</strong> &mdash; Can manage horses and post updates only. Ideal for stable staff who just need to share horse news.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips & FAQ -->
    <div class="mb-8">
        <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stable-100 bg-stable-50">
                <h2 class="text-lg font-semibold text-stable-900">Tips & Frequently Asked Questions</h2>
            </div>
            <div class="px-6 py-5 space-y-5 text-sm text-stable-700">
                <div>
                    <h3 class="font-medium text-stable-900 mb-1">A sponsor says they didn't get their welcome email</h3>
                    <p class="text-stable-600">First, ask them to check their spam/junk folder. If it's not there, check that your SMTP settings are configured correctly in <strong>Settings &rarr; Email</strong>. You can resend emails from the Sponsor's detail page or use the test email feature in Diagnostics.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">A payment failed for a sponsor</h3>
                    <p class="text-stable-600">Stripe automatically retries failed payments over several days. The sponsorship status will show as "Past Due" during this time. If retries fail, the subscription will eventually be cancelled. You don't need to do anything &mdash; Stripe handles recovery attempts automatically.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">How do I change a sponsor's horse?</h3>
                    <p class="text-stable-600">Currently, a sponsor would need to cancel their existing sponsorship and create a new one for the different horse. You can cancel it on their behalf from their sponsor detail page.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">How often should I post updates?</h3>
                    <p class="text-stable-600">We recommend posting at least one update per horse per month. Regular updates keep sponsors engaged and feeling connected. Even a short note with a photo goes a long way!</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">Can I have multiple photos per horse?</h3>
                    <p class="text-stable-600">Yes! You can upload multiple photos when creating or editing a horse. The first photo is used as the main profile image in the gallery. Additional photos are shown on the horse's detail page.</p>
                </div>

                <div>
                    <h3 class="font-medium text-stable-900 mb-1">What happens if I disconnect Stripe?</h3>
                    <p class="text-stable-600">Disconnecting Stripe will prevent new sponsorships from being created but <strong>will not</strong> cancel existing subscriptions. Existing sponsors will continue to be billed through your Stripe account until manually cancelled.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
