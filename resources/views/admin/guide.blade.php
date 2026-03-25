<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">QMS Guide</h2>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-purple-100 text-purple-600">Admin only</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- QMS Health Overview --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $totalDocs }}</div>
                    <div class="text-xs text-gray-500 mt-1">Documents</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $approvedCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Approved</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold {{ $unresolvedComments > 0 ? 'text-amber-600' : 'text-gray-800' }}">{{ $unresolvedComments }}</div>
                    <div class="text-xs text-gray-500 mt-1">Open Comments</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $recordCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Records</div>
                </div>
            </div>

            {{-- What is a QMS --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">What is this QMS?</h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-3">
                    A Quality Management System (QMS) is a set of documents and processes that prove your company builds safe medical devices. The Notified Body (Scarlet) will review these documents to decide if Therapeak gets a CE mark.
                </p>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Think of it as a rulebook your company follows. The documents describe <strong>what</strong> you do, <strong>how</strong> you do it, and <strong>proof</strong> that you actually do it. The "what" and "how" are SOPs (procedures). The "proof" are records (filled forms, meeting notes, reports).
                </p>
            </div>

            {{-- Your Regular Tasks --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">What You Need to Do</h3>
                    <p class="text-xs text-gray-500 mt-1">These are the things only you can do. Claude Code handles everything else.</p>
                </div>

                <div class="divide-y divide-gray-100">
                    {{-- Publish Changes --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Publish Changes</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: After document edits are made</p>
                                <p class="text-sm text-gray-600 mt-1">When Claude or you edit a document, the changes are saved but not yet "official." Go to <a href="{{ route('documents.changes') }}" class="text-blue-600 underline">Unpublished Changes</a>, review them, and click Publish. This saves everything to git — creating a permanent record.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> ISO 13485 requires controlled document changes with an audit trail. Publishing = approval.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Review Comments --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Review & Resolve Comments</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: Weekly, or when you see the comment badge</p>
                                <p class="text-sm text-gray-600 mt-1">Comments are review notes on documents. "Required Change" comments block document approval — you must resolve them. Click "Resolve" when addressed, add a note explaining what you did.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> Proves documents go through a proper review process before approval.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Management Review --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Management Review</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: Every 6 months</p>
                                <p class="text-sm text-gray-600 mt-1">You sit down (even for 30 minutes) and review: Is the QMS working? Any complaints? Any CAPAs? Any audit findings? Fill in the Management Review form with your conclusions and any decisions.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> ISO 13485 clause 5.6 — top management must review the QMS periodically.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Handle Complaints --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Log Complaints</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: When a user reports a problem</p>
                                <p class="text-sm text-gray-600 mt-1">If a user emails about a problem with the therapy (not billing issues — actual product problems), log it in the Complaint Form. If it's serious (someone got hurt or could have), also fill in the CAPA form.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> EU MDR Article 87-92 requires tracking complaints and reporting serious incidents.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Post-Market Surveillance --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Post-Market Surveillance</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: Quarterly review, annual report</p>
                                <p class="text-sm text-gray-600 mt-1">Collect data about how the device performs in the real world: user feedback, mood rating trends, complaints, AI response quality. Write a brief PMS report. Claude can help draft it.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> EU MDR Articles 83-86 — you must actively monitor your device after it's on the market.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Training --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Training Records</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: When you learn something new about the QMS</p>
                                <p class="text-sm text-gray-600 mt-1">When Suzan teaches you something, when you read a QMS document and understand it, or when a process changes — record it in the Training Form. Just note what you learned, when, and who taught you.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> ISO 13485 clause 6.2 — people working on the QMS must be competent and trained.</p>
                            </div>
                        </div>
                    </div>

                    {{-- CAPA --}}
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">CAPA (Corrective & Preventive Actions)</h4>
                                <p class="text-xs text-gray-500 mt-0.5">When: When something goes wrong and needs fixing</p>
                                <p class="text-sm text-gray-600 mt-1">If an audit finding, serious complaint, or systematic problem is found — open a CAPA. It documents: what went wrong, why (root cause), what you did to fix it, and how you'll prevent it from happening again.</p>
                                <p class="text-xs text-gray-400 mt-1"><strong>Why:</strong> ISO 13485 clause 8.5.2/8.5.3 — you must have a process for fixing problems systematically.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Reference --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Reference: When Does What</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 pr-4 text-xs font-semibold text-gray-500 uppercase">Task</th>
                                <th class="text-left py-2 pr-4 text-xs font-semibold text-gray-500 uppercase">Frequency</th>
                                <th class="text-left py-2 text-xs font-semibold text-gray-500 uppercase">Done by</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr><td class="py-2 pr-4 text-gray-700">Publish document changes</td><td class="py-2 pr-4 text-gray-500">As needed</td><td class="py-2 text-gray-500">You</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Review comments</td><td class="py-2 pr-4 text-gray-500">Weekly</td><td class="py-2 text-gray-500">You</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Log complaints</td><td class="py-2 pr-4 text-gray-500">When they come in</td><td class="py-2 text-gray-500">You</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">PMS data review</td><td class="py-2 pr-4 text-gray-500">Quarterly</td><td class="py-2 text-gray-500">You + Claude</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Management review</td><td class="py-2 pr-4 text-gray-500">Every 6 months</td><td class="py-2 text-gray-500">You</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Supplier review</td><td class="py-2 pr-4 text-gray-500">Annually</td><td class="py-2 text-gray-500">You</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Internal audit</td><td class="py-2 pr-4 text-gray-500">Annually</td><td class="py-2 text-gray-500">External auditor</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">PMS report</td><td class="py-2 pr-4 text-gray-500">Annually</td><td class="py-2 text-gray-500">You + Claude</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Update QMS documents</td><td class="py-2 pr-4 text-gray-500">When processes change</td><td class="py-2 text-gray-500">Claude</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Create/update records</td><td class="py-2 pr-4 text-gray-500">As needed</td><td class="py-2 text-gray-500">Claude</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">CAPA</td><td class="py-2 pr-4 text-gray-500">When problems arise</td><td class="py-2 text-gray-500">You</td></tr>
                            <tr><td class="py-2 pr-4 text-gray-700">Training records</td><td class="py-2 pr-4 text-gray-500">When you learn something</td><td class="py-2 text-gray-500">You</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Immediate Action Items --}}
            <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-red-700 mb-3">Action Items Before April 7</h3>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mt-0.5" disabled>
                        <div>
                            <span class="font-medium text-gray-800">Sign Hetzner DPA</span>
                            <span class="block text-xs text-gray-500">Go to hetzner.com/legal/data-processing-agreement and sign it</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mt-0.5" disabled>
                        <div>
                            <span class="font-medium text-gray-800">Verify server backups</span>
                            <span class="block text-xs text-gray-500">Check Hetzner panel: are backups enabled? What frequency?</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mt-0.5" disabled>
                        <div>
                            <span class="font-medium text-gray-800">Create Scarlet auditor account</span>
                            <span class="block text-xs text-gray-500">Create a user with "auditor" role for Scarlet to review the QMS</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mt-0.5" disabled>
                        <div>
                            <span class="font-medium text-gray-800">Review QMS with Suzan</span>
                            <span class="block text-xs text-gray-500">Walk through all documents with your consultant before March 31 audit</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mt-0.5" disabled>
                        <div>
                            <span class="font-medium text-gray-800">Internal audit (March 31)</span>
                            <span class="block text-xs text-gray-500">Jurist will audit — address any findings before April 7</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Key Terms --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Key Terms (Jargon Buster)</h3>
                <div class="space-y-3 text-sm">
                    <div><span class="font-semibold text-gray-700">QMS</span> <span class="text-gray-500">— Quality Management System. The collection of all your processes and documents.</span></div>
                    <div><span class="font-semibold text-gray-700">SOP</span> <span class="text-gray-500">— Standard Operating Procedure. A document that describes how you do something step-by-step.</span></div>
                    <div><span class="font-semibold text-gray-700">CAPA</span> <span class="text-gray-500">— Corrective and Preventive Action. A formal process for fixing problems and preventing them from recurring.</span></div>
                    <div><span class="font-semibold text-gray-700">PMS</span> <span class="text-gray-500">— Post-Market Surveillance. Monitoring your device after it's on the market to catch problems early.</span></div>
                    <div><span class="font-semibold text-gray-700">PMCF</span> <span class="text-gray-500">— Post-Market Clinical Follow-up. Collecting clinical evidence about your device while it's being used.</span></div>
                    <div><span class="font-semibold text-gray-700">NB</span> <span class="text-gray-500">— Notified Body. The organization (Scarlet) that audits your QMS and grants CE marking.</span></div>
                    <div><span class="font-semibold text-gray-700">CE Mark</span> <span class="text-gray-500">— The certification that allows you to sell a medical device in the EU.</span></div>
                    <div><span class="font-semibold text-gray-700">MDR</span> <span class="text-gray-500">— Medical Device Regulation (EU 2017/745). The law governing medical devices in Europe.</span></div>
                    <div><span class="font-semibold text-gray-700">ISO 13485</span> <span class="text-gray-500">— The international standard for medical device quality management systems.</span></div>
                    <div><span class="font-semibold text-gray-700">Risk Management</span> <span class="text-gray-500">— Identifying what could go wrong with your device and documenting how you prevent/mitigate it.</span></div>
                    <div><span class="font-semibold text-gray-700">Clinical Evaluation</span> <span class="text-gray-500">— Reviewing clinical evidence (studies, literature, your own data) that your device is safe and performs as intended.</span></div>
                    <div><span class="font-semibold text-gray-700">Vigilance</span> <span class="text-gray-500">— Reporting serious incidents involving your device to authorities within specific timeframes.</span></div>
                    <div><span class="font-semibold text-gray-700">IFU</span> <span class="text-gray-500">— Instructions for Use. What users need to know to use the device safely.</span></div>
                    <div><span class="font-semibold text-gray-700">SaMD</span> <span class="text-gray-500">— Software as a Medical Device. Software that IS the medical device (like Therapeak), not software that controls a physical device.</span></div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
