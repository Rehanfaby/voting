@extends('layout.main')
@section('content')

<section class="forms">
    <div class="container-fluid">
        <div class="card mg-help-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                <h4 class="mb-0"><i class="dripicons-question"></i> {{ trans('file.Help') }} — {{ trans('file.User Guide') }}</h4>
                <small class="text-muted">{{ config('app.version_label') }} · mulemagc.com</small>
            </div>
            <div class="card-body">
                <p class="mg-help-intro">
                    Full platform guide for staff. Use the tabs below to find a topic.
                    Screenshots are live captures from <strong>mulemagc.com</strong>.
                    Every backend menu also has a <strong>Help</strong> tab (last) with a short guide for that area.
                </p>

                <ul class="nav nav-tabs sc-tabs mg-help-tabs" id="help-tab-nav" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#help-start" role="tab">Getting Started</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-public" role="tab">Public Site &amp; Vote Now</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-zones" role="tab">Green &amp; Orange Zones</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-rate" role="tab">Rate Us</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-site" role="tab">Site Content</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-contestants" role="tab">Contestants</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-tickets" role="tab">Tickets &amp; Halls</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-payments" role="tab">Payments</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-announce" role="tab">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-grading" role="tab">Grading</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-login" role="tab">Login &amp; Roles</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#help-trouble" role="tab">Troubleshooting</a></li>
                </ul>

                <div class="tab-content mg-help-content" id="help-tab-content">

                    {{-- Getting Started --}}
                    <div class="tab-pane fade show active" id="help-start" role="tabpanel">
                        <h5>Welcome to Mulema Gospel Talent admin</h5>
                        <p>This panel manages contestants, public voting, tickets, site content, announcements, and grading.</p>
                        <figure class="mg-help-shot">
                            <a href="{{ url('/') }}" target="_blank" rel="noopener">
                                <img src="{{ asset('public/img/help/home.jpg') }}?v={{ config('app.version') }}" alt="Live homepage screenshot" loading="lazy">
                            </a>
                            <figcaption>Live homepage — <a href="{{ url('/') }}" target="_blank">mulemagc.com</a></figcaption>
                        </figure>
                        <div class="mg-help-box">
                            <strong>Quick links</strong>
                            <ul class="mb-0">
                                <li><a href="{{ url('/') }}" target="_blank">Public homepage</a></li>
                                <li><a href="{{ route('team') }}" target="_blank">Vote Now (Contestants)</a></li>
                                <li><a href="{{ route('setting.site_content') }}">Site Content</a></li>
                                <li><a href="{{ route('login') }}" target="_blank">Staff login page</a></li>
                            </ul>
                        </div>
                        <h6>Typical weekly flow</h6>
                        <ol class="mg-help-steps">
                            <li>Approve / update contestants under <strong>Contestants</strong>.</li>
                            <li>Set Green/Orange zone count in <strong>Site Content → Eliminations for the week</strong>.</li>
                            <li>Monitor votes under <strong>Vote</strong>; reconcile pending payments if needed.</li>
                            <li>Moderate <strong>Rate Us</strong> reviews before they appear publicly.</li>
                            <li>Send WhatsApp updates via <strong>Announcements</strong>.</li>
                        </ol>
                        <h6>Where things live</h6>
                        <table class="table table-sm table-bordered mg-help-table">
                            <thead><tr><th>Task</th><th>Menu</th></tr></thead>
                            <tbody>
                                <tr><td>Homepage sections, popup, Rate Us, zones, gallery</td><td>Site Content</td></tr>
                                <tr><td>Vote price, voting window, hide votes</td><td>Settings → General Setting / Vote settings</td></tr>
                                <tr><td>Contestant profiles &amp; approval</td><td>Contestants</td></tr>
                                <tr><td>Ticket products &amp; hall seating</td><td>Ticket / Product · Halls</td></tr>
                                <tr><td>Judge / ambassador points</td><td>Judge Grading · Ambassador Grading · Grading</td></tr>
                                <tr><td>Side menu order (drag &amp; drop)</td><td>Site Content → Side Menu</td></tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Public site --}}
                    <div class="tab-pane fade" id="help-public" role="tabpanel">
                        <h5>Public website &amp; Vote Now</h5>
                        <p>Visitors use the public site to browse contestants, vote, buy tickets, and rate the platform.</p>
                        <div class="mg-help-shot-grid">
                            <figure class="mg-help-shot">
                                <a href="{{ route('team') }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('public/img/help/contestants.jpg') }}?v={{ config('app.version') }}" alt="Vote Now live screenshot" loading="lazy">
                                </a>
                                <figcaption>Vote Now / Contestants — <a href="{{ route('team') }}" target="_blank">/contestants</a></figcaption>
                            </figure>
                            <figure class="mg-help-shot">
                                <a href="{{ route('about') }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('public/img/help/about.jpg') }}?v={{ config('app.version') }}" alt="About Us live screenshot" loading="lazy">
                                </a>
                                <figcaption>About Us — <a href="{{ route('about') }}" target="_blank">/about</a></figcaption>
                            </figure>
                        </div>
                        <h6>Vote Now page</h6>
                        <ul>
                            <li>URL: <code>/contestants</code> (old <code>/musician/team</code> redirects here).</li>
                            <li>Contestants are ranked by successful vote totals (highest first).</li>
                            <li>Each card links to <code>/contestant/{id}</code>.</li>
                            <li>Search (header or page) filters by name. Zone blink speeds up on search hits.</li>
                        </ul>
                        <h6>Casting a vote</h6>
                        <figure class="mg-help-video">
                            <video controls playsinline preload="metadata">
                                <source src="{{ asset('public/videos/help/how-to-vote.mp4') }}?v={{ config('app.version') }}" type="video/mp4">
                            </video>
                            <figcaption>Silent guide — How to vote</figcaption>
                        </figure>
                        <ol class="mg-help-steps">
                            <li>Open a contestant → choose number of votes.</li>
                            <li>Enter voter name, MoMo/OM number, WhatsApp number (or Visa/card).</li>
                            <li>Approve the Mobile Money prompt on the phone, or complete Stripe checkout.</li>
                            <li>Votes count only after payment is confirmed (status successful).</li>
                            <li>After success, users are invited to <strong>Rate Us</strong>.</li>
                        </ol>
                        <div class="mg-help-box mg-help-box--warn">
                            Pending payments do not increase the public total. Use Vote list + reconciliation tools if a voter paid but votes did not appear.
                        </div>
                        <h6>Header Login</h6>
                        <p>The gold <strong>Login</strong> button on the public header opens the dashboard sign-in page (<code>/login</code>) for staff, contestants with accounts, and other users. Logged-in users see their account menu instead.</p>
                    </div>

                    {{-- Zones --}}
                    <div class="tab-pane fade" id="help-zones" role="tabpanel">
                        <h5>Green Zone &amp; Orange Zone</h5>
                        <p>These zones appear on Vote Now when eliminations are enabled. They create urgency for the public to vote.</p>
                        <figure class="mg-help-shot">
                            <a href="{{ route('team') }}" target="_blank" rel="noopener">
                                <img src="{{ asset('public/img/help/contestants.jpg') }}?v={{ config('app.version') }}" alt="Green and Orange zones on Vote Now" loading="lazy">
                            </a>
                            <figcaption>Live Vote Now showing zone frames (green = safe, orange = at risk when enabled).</figcaption>
                        </figure>
                        <h6>How to configure</h6>
                        <ol class="mg-help-steps">
                            <li>Go to <strong>Site Content → Eliminations for the week</strong>.</li>
                            <li>Enable the red-line feature (now shown as Green / Orange zones).</li>
                            <li>Set <strong>No. of eliminations</strong> = how many contestants sit in the Orange Zone (bottom of the list).</li>
                            <li>Save, then open Vote Now to verify.</li>
                        </ol>
                        <table class="table table-sm table-bordered mg-help-table">
                            <thead><tr><th>Zone</th><th>Meaning</th><th>Visual</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><strong>Green Zone</strong></td>
                                    <td>Top contestants safely above the cut line</td>
                                    <td>Green banner + blinking green frame</td>
                                </tr>
                                <tr>
                                    <td><strong>Orange Zone</strong></td>
                                    <td>Bottom N contestants at risk of elimination</td>
                                    <td>Orange banner + blinking orange frame</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mg-help-box">
                            <strong>Example:</strong> 58 contestants, eliminations = 20 → Green Zone = top 38, Orange Zone = bottom 20.
                        </div>
                        <p>Disable the feature anytime to hide both banners and stop the blink borders.</p>
                    </div>

                    {{-- Rate Us --}}
                    <div class="tab-pane fade" id="help-rate" role="tabpanel">
                        <h5>Rate Us</h5>
                        <p>Collect 1–5 star ratings and comments after voting (or from the Rate Us page).</p>
                        <figure class="mg-help-shot">
                            <a href="{{ route('rate.us') }}" target="_blank" rel="noopener">
                                <img src="{{ asset('public/img/help/rate-us.jpg') }}?v={{ config('app.version') }}" alt="Rate Us live screenshot" loading="lazy">
                            </a>
                            <figcaption>Rate Us page — average + reviews first, then the Rate Us button — <a href="{{ route('rate.us') }}" target="_blank">/rate-us</a></figcaption>
                        </figure>
                        <h6>Public experience</h6>
                        <ul>
                            <li>Page shows the overall average and approved reviews first.</li>
                            <li>Post-vote ratings appear before ratings with no vote link.</li>
                            <li><strong>Rate Us</strong> button opens a mobile-friendly form modal.</li>
                            <li>Fields: stars, show as voter/contestant, name, searchable country (default Cameroon), comment.</li>
                            <li>New ratings stay hidden until an admin enables them.</li>
                        </ul>
                        <h6>Admin moderation</h6>
                        <ol class="mg-help-steps">
                            <li>Open <strong>Site Content → Rate Us</strong>.</li>
                            <li>Enable/disable the public Rate Us page and post-vote link.</li>
                            <li>Check <strong>Show</strong> next to reviews that should appear on the site.</li>
                            <li>Press <strong>Save visibility</strong>.</li>
                        </ol>
                        <p>Visible average (public) and all-ratings average (admin) are both shown on that tab.</p>
                    </div>

                    {{-- Site Content --}}
                    <div class="tab-pane fade" id="help-site" role="tabpanel">
                        <h5>Site Content</h5>
                        <p>Control what the public homepage and Vote Now experience show. Path: <strong>Site Content</strong> in the side menu.</p>
                        <table class="table table-sm table-bordered mg-help-table">
                            <thead><tr><th>Tab</th><th>What it does</th></tr></thead>
                            <tbody>
                                <tr><td>Sections</td><td>Turn homepage blocks on/off (judges, casting, most voted, etc.).</td></tr>
                                <tr><td>Popup</td><td>Homepage flyer image, optional link &amp; countdown.</td></tr>
                                <tr><td>Most Voted &amp; Hero</td><td>How many top contestants to feature; EN/FR hero banners.</td></tr>
                                <tr><td>Eliminations for the week</td><td>Enable Green/Orange zones and set N.</td></tr>
                                <tr><td>Rate Us</td><td>Enable page + approve which ratings show.</td></tr>
                                <tr><td>Casting / Primes</td><td>Provincial calendar and finals schedule with countdowns.</td></tr>
                                <tr><td>Gallery / About / Judges / Ambassadors / Logos</td><td>Media and people content for the public site.</td></tr>
                                <tr><td>Landing Menu</td><td>Order of public header links.</td></tr>
                                <tr><td>Side Menu</td><td>Order of admin menu — drag handle or arrows, then Save.</td></tr>
                            </tbody>
                        </table>
                        <div class="mg-help-box">
                            After saving a Site Content tab, the page returns to that same tab (URL hash).
                        </div>
                    </div>

                    {{-- Contestants --}}
                    <div class="tab-pane fade" id="help-contestants" role="tabpanel">
                        <h5>Contestants</h5>
                        <ul>
                            <li>Create and edit contestant profiles (photo, bio, gallery, social links).</li>
                            <li>Only <strong>active + approved</strong> contestants appear on Vote Now.</li>
                            <li>Pending contestants stay in the pending list until approved.</li>
                            <li>Public profile URL: <code>/contestant/{id}</code>.</li>
                            <li>For WhatsApp vote alerts, contestants need a valid <strong>phone number</strong> on their record.</li>
                        </ul>
                        <h6>Votes menu</h6>
                        <p>Use <strong>Vote</strong> to review successful, pending, and failed payments. Filter by status when investigating missing votes.</p>
                    </div>

                    {{-- Tickets --}}
                    <div class="tab-pane fade" id="help-tickets" role="tabpanel">
                        <h5>Tickets &amp; Halls</h5>
                        <figure class="mg-help-shot">
                            <a href="{{ route('events') }}" target="_blank" rel="noopener">
                                <img src="{{ asset('public/img/help/events.jpg') }}?v={{ config('app.version') }}" alt="Buy Tickets / Events live screenshot" loading="lazy">
                            </a>
                            <figcaption>Buy Tickets (events) — <a href="{{ route('events') }}" target="_blank">/events</a></figcaption>
                        </figure>
                        <ul>
                            <li><strong>Ticket / Product</strong>: create event tickets (name, code, event, price, seats, image, details).</li>
                            <li>When creating a ticket, fill Ticket Name and other required fields; Event uses the dropdown (selectpicker).</li>
                            <li><strong>Halls</strong>: define hall layouts, publish a layout version, attach it to a ticket/product.</li>
                            <li>Set seat category prices (VIP / Premium / Regular) on inventory — not on the Event row alone.</li>
                            <li>Public ticket purchase is under Buy Tickets on the website.</li>
                        </ul>
                    </div>

                    {{-- Payments --}}
                    <div class="tab-pane fade" id="help-payments" role="tabpanel">
                        <h5>Payments (votes &amp; tickets)</h5>
                        <ul>
                            <li><strong>MTN MoMo / Orange Money</strong> via the configured mobile-money gateway (Campay / PawaPay).</li>
                            <li><strong>Visa / Mastercard</strong> via Stripe Checkout.</li>
                            <li>Voters receive WhatsApp confirmation after success (when WhatsApp is configured).</li>
                            <li>Contestants can receive a vote alert when a payment succeeds (needs phone on contestant).</li>
                            <li>Card failures can notify the voter with a readable reason when Stripe webhooks are enabled.</li>
                        </ul>
                        <div class="mg-help-box mg-help-box--warn">
                            Do not mark a vote successful unless payment is confirmed. Prefer gateway status / webhook / reconcile tools over manual edits.
                        </div>
                    </div>

                    {{-- Announcements --}}
                    <div class="tab-pane fade" id="help-announce" role="tabpanel">
                        <h5>Announcements (WhatsApp)</h5>
                        <ol class="mg-help-steps">
                            <li>Open <strong>Announcements</strong>.</li>
                            <li>Optionally start from a bilingual template.</li>
                            <li>Select recipients (contestants, voters, custom lists as available).</li>
                            <li>Schedule or send immediately; attachments are supported when configured.</li>
                            <li>Each sent message can get an auto reference number (Settings for reference format).</li>
                        </ol>
                        <p>Scheduled announcements are processed by the server cron — ensure cron is running on production.</p>
                    </div>

                    {{-- Grading --}}
                    <div class="tab-pane fade" id="help-grading" role="tabpanel">
                        <h5>Judge &amp; Ambassador grading</h5>
                        <figure class="mg-help-video">
                            <video controls playsinline preload="metadata">
                                <source src="{{ asset('public/videos/help/how-to-grade.mp4') }}?v={{ config('app.version') }}" type="video/mp4">
                            </video>
                            <figcaption>Silent guide — How to grade as a Judge and as an Ambassador</figcaption>
                        </figure>
                        <ul>
                            <li><strong>Judge Grading</strong> / <strong>Ambassador Grading</strong>: enter points for contestants.</li>
                            <li><strong>Grading Setting</strong>: weights (vote %, judge %, ambassador %), elimination count for grading lists.</li>
                            <li>Public “Most Voted” and Vote Now zones are separate from grading elimination lists — configure each in its own place.</li>
                        </ul>
                        <div class="mg-help-shot-grid">
                            <figure class="mg-help-shot">
                                <img src="{{ asset('public/img/help/grade-judge.jpg') }}?v={{ config('app.version') }}" alt="Judge grading steps" loading="lazy">
                                <figcaption>Judge grading steps</figcaption>
                            </figure>
                            <figure class="mg-help-shot">
                                <img src="{{ asset('public/img/help/grade-ambassador.jpg') }}?v={{ config('app.version') }}" alt="Ambassador grading steps" loading="lazy">
                                <figcaption>Ambassador grading steps</figcaption>
                            </figure>
                        </div>
                    </div>

                    {{-- Login --}}
                    <div class="tab-pane fade" id="help-login" role="tabpanel">
                        <h5>Login &amp; roles</h5>
                        <figure class="mg-help-shot">
                            <a href="{{ route('login') }}" target="_blank" rel="noopener">
                                <img src="{{ asset('public/img/help/login.jpg') }}?v={{ config('app.version') }}" alt="Staff login live screenshot" loading="lazy">
                            </a>
                            <figcaption>Staff / dashboard login — opened from the public gold <strong>Login</strong> button — <a href="{{ route('login') }}" target="_blank">/login</a></figcaption>
                        </figure>
                        <ul>
                            <li>Staff login: public header <strong>Login</strong> or <code>/login</code> (username + password).</li>
                            <li>Forgot password: reset via WhatsApp OTP from the login card.</li>
                            <li>After login, OTP verification may be required depending on role / settings.</li>
                            <li><strong>Role Permission</strong> (Admin): control which menus each role can open.</li>
                            <li>User Profile is under Settings for the signed-in account.</li>
                        </ul>
                        <div class="mg-help-box">
                            Help is listed under Settings so staff can find it quickly. Future module tabs can expose the same topics for roles without Settings permission.
                        </div>
                    </div>

                    {{-- Troubleshooting --}}
                    <div class="tab-pane fade" id="help-trouble" role="tabpanel">
                        <h5>Troubleshooting</h5>
                        <table class="table table-sm table-bordered mg-help-table">
                            <thead><tr><th>Problem</th><th>What to check</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>Vote Now shows “musician” URLs</td>
                                    <td>Use <code>/contestants</code>. Old links redirect automatically.</td>
                                </tr>
                                <tr>
                                    <td>Zones not visible</td>
                                    <td>Site Content → Eliminations enabled + N &gt; 0 and N &lt; total contestants.</td>
                                </tr>
                                <tr>
                                    <td>Rating not on public page</td>
                                    <td>Site Content → Rate Us → tick Show → Save. Feature must be enabled.</td>
                                </tr>
                                <tr>
                                    <td>Ticket create says Ticket Name required though filled</td>
                                    <td>Hard-refresh admin page (fixed by scoping validation to the ticket form).</td>
                                </tr>
                                <tr>
                                    <td>Paid but votes missing</td>
                                    <td>Vote list status; payment pending page; gateway reconcile / Stripe dashboard.</td>
                                </tr>
                                <tr>
                                    <td>No WhatsApp messages</td>
                                    <td>Wasender / UltraMsg credentials in .env; recipient phone format; cron for schedules.</td>
                                </tr>
                                <tr>
                                    <td>Side menu order wrong</td>
                                    <td>Site Content → Side Menu → drag or arrows → Save. Refresh admin.</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="mb-0 text-muted">
                            Support: Sr. Engr. Tefu R. Mbole ·
                            <a href="https://wa.me/237675321739" target="_blank" rel="noopener">(+237) 675-321-739</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $("#setting").addClass("show");
    $("#help-setting-menu").addClass("active");
    // Open tab from hash (e.g. /setting/help#help-rate)
    $(function () {
        var hash = window.location.hash;
        if (hash && $('#help-tab-nav a[href="' + hash + '"]').length) {
            $('#help-tab-nav a[href="' + hash + '"]').tab('show');
        }
        $('#help-tab-nav a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if (history.replaceState) {
                history.replaceState(null, null, e.target.getAttribute('href'));
            }
        });
    });
</script>
@endsection

<style>
.mg-help-card { border: 0; box-shadow: 0 8px 28px rgba(15, 23, 42, .08); }
.mg-help-intro { color: #64748b; margin-bottom: 18px; }
.mg-help-tabs { border-bottom: none; margin-bottom: 18px; flex-wrap: wrap; gap: 6px; }
.mg-help-tabs .nav-link {
    color: #334155; font-weight: 700; border: 2px solid #cbd5e1; border-radius: 30px;
    padding: 7px 14px; background: #fff;
}
.mg-help-tabs .nav-link.active { background: #0a2350; border-color: #0a2350; color: #fff; }
.mg-help-content { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 18px 10px; }
.mg-help-content h5 { color: #0a2350; font-weight: 800; margin-bottom: 10px; }
.mg-help-content h6 { color: #0a2350; font-weight: 700; margin-top: 16px; }
.mg-help-box {
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
    padding: 12px 14px; margin: 12px 0;
}
.mg-help-box--warn { background: #fff7ed; border-color: #fed7aa; }
.mg-help-steps { padding-left: 20px; }
.mg-help-steps li { margin-bottom: 6px; }
.mg-help-table th { background: #0a2350; color: #fff; }
.mg-help-content code {
    background: #e2e8f0; padding: 1px 6px; border-radius: 4px; font-size: 12px;
}
.mg-help-shot {
    margin: 12px 0 18px; background: #fff; border: 1px solid #e2e8f0;
    border-radius: 12px; overflow: hidden; box-shadow: 0 6px 18px rgba(15,23,42,.06);
}
.mg-help-shot img {
    display: block; width: 100%; height: auto; max-height: 420px; object-fit: cover; object-position: top;
    border-bottom: 1px solid #e2e8f0;
}
.mg-help-shot figcaption {
    padding: 8px 12px; font-size: 12px; color: #64748b; margin: 0;
}
.mg-help-shot-grid {
    display: grid; gap: 14px; margin-bottom: 8px;
}
.mg-help-video {
    margin: 12px 0 18px; background: #0a2350; border-radius: 12px; overflow: hidden;
}
.mg-help-video video { display: block; width: 100%; max-height: 420px; background: #000; }
.mg-help-video figcaption { padding: 8px 12px; font-size: 12px; color: #e2e8f0; margin: 0; }
@media (min-width: 992px) {
    .mg-help-shot-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 767.98px) {
    .mg-help-content { padding: 14px 12px; }
    .mg-help-tabs .nav-link { font-size: 12px; padding: 6px 10px; }
    .mg-help-shot img { max-height: 260px; }
}
</style>
