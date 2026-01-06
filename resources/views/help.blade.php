@extends('layouts.app')

@section('title', 'Help & FAQs')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md mt-10">

    <h1 class="text-4xl font-bold text-orange-500 mb-6 text-center">Help Center</h1>

    <p class="mb-6 text-gray-700 dark:text-gray-200">
        Welcome to the Help Center! Here you can find guidance on how to use the ROPA system efficiently.
        Use the sections below to navigate common tasks, FAQs, and tips.
    </p>

    <!-- Importance Section -->
    <section class="mb-8">
        <h2 class="text-3xl font-semibold text-orange-400 mb-4">Why Submitting a ROPA is Important</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-2">
            A Record of Processing Activity (ROPA) is a crucial document that helps your organization comply with data protection regulations such as GDPR. 
            It provides a clear overview of how personal data is collected, processed, stored, and shared within your organization.
        </p>
        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
            <li>Ensures transparency and accountability in data handling.</li>
            <li>Helps identify potential risks to personal data.</li>
            <li>Supports compliance audits and legal requirements.</li>
            <li>Improves trust with clients, employees, and stakeholders.</li>
            <li>Helps maintain accurate and up-to-date records of all processing activities.</li>
        </ul>
    </section>

    <!-- FAQ Section -->
    <section class="mb-8">
        <h2 class="text-3xl font-semibold text-orange-400 mb-4">Frequently Asked Questions</h2>

        <div class="space-y-3">
            <details class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <summary class="cursor-pointer font-semibold text-gray-800 dark:text-gray-100">
                    How do I add a new ROPA record?
                </summary>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    Go to the <strong>Add Process</strong> menu in the sidebar. Fill in the required details and submit the form to create a new record.
                </p>
            </details>

            <details class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <summary class="cursor-pointer font-semibold text-gray-800 dark:text-gray-100">
                    How do I share a ROPA record?
                </summary>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    Click the <strong>Share</strong> button in your ROPA dashboard. Enter the recipient's email, optional CC addresses, choose the format (PDF/Excel), and send.
                </p>
            </details>

            <details class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <summary class="cursor-pointer font-semibold text-gray-800 dark:text-gray-100">
                    How can I print a ROPA record?
                </summary>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    Click the <strong>Print</strong> button next to the record in your dashboard. A printable view will open in a new tab.
                </p>
            </details>

            <details class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <summary class="cursor-pointer font-semibold text-gray-800 dark:text-gray-100">
                    How do I search and filter records?
                </summary>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    Use the search bar above your ROPA table to search by organisation or department. You can also filter by status (Pending or Reviewed) using the dropdown.
                </p>
            </details>

            <details class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <summary class="cursor-pointer font-semibold text-gray-800 dark:text-gray-100">
                    Need more assistance?
                </summary>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    Contact <a href="mailto:support@example.com" class="text-blue-600 underline">coms@acrn.com</a> with your query. Include your user ID and record details for faster support.
                </p>
            </details>
        </div>
    </section>

    <!-- Tips Section -->
    <section class="mb-8">
        <h2 class="text-3xl font-semibold text-orange-400 mb-4">Tips for Using the ROPA System</h2>
        <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
            <li>Always double-check the data before submitting a ROPA record.</li>
            <li>Use filters and search to locate records quickly.</li>
            <li>Ensure you select the correct format (PDF or Excel) when sharing records.</li>
            <li>Keep track of shared records using the <strong>Status</strong> column in your dashboard.</li>
            <li>If you notice any errors, update the record immediately or contact support.</li>
        </ul>
    </section>

    <!-- Contact Section -->
    <section>
        <h2 class="text-3xl font-semibold text-orange-400 mb-4">Contact & Support</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-2">
            For any technical issues, feature requests, or general questions, reach out to our support team:
        </p>
        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
            <li>Email: <a href="mailto:support@example.com" class="text-blue-600 underline">compliance@acrnhealth.com</a></li>
            <li>Phone: +263 787780405</li>
        </ul>
    </section>
</div>
@endsection
