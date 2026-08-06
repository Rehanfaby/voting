<?php

/**
 * Per-menu in-app user guides (shown as the last Help tab in the admin shell).
 * Keys match sidebar data-menu-key values from SiteContent::menuKeys().
 */
return [

    'dashboard' => [
        'title' => 'Dashboard',
        'intro' => 'Your home overview of voting activity, tickets, and quick links into the rest of the admin.',
        'steps' => [
            'Open Dashboard after login to see high-level activity.',
            'Use the side menu (or the colorful section tabs) to jump into Votes, Contestants, Tickets, or Site Content.',
            'Check pending votes and recent ticket sales before the weekly show.',
        ],
        'tips' => [
            'Side menu order is editable under Site Content → Side Menu (drag and drop).',
            'Full guides for every topic also live under Settings → Help.',
        ],
        'shots' => [
            ['file' => 'home.jpg', 'caption' => 'Public homepage (what visitors see)', 'url' => '/'],
        ],
    ],

    'product' => [
        'title' => 'Tickets & Products',
        'intro' => 'Create ticket products, categories/events, scan entry, and review tickets sold.',
        'steps' => [
            'Category — create the event / show category first.',
            'Product list / Add product — set ticket name, price, dates, and optional hall seat map.',
            'Ticket Scan — open the scan screen at the door to validate QR tickets.',
            'Tickets Sold — search and export purchases.',
        ],
        'tips' => [
            'Ticket Name is required on the product form (not on hall layout modals).',
            'Attach a Hall layout when you need assigned seating.',
        ],
        'shots' => [
            ['file' => 'events.jpg', 'caption' => 'Public Events / tickets page', 'url' => '/events'],
        ],
    ],

    'halls' => [
        'title' => 'Halls',
        'intro' => 'Define venue halls and seat layouts used by ticket products.',
        'steps' => [
            'Create a Hall with a clear name (e.g. Main Auditorium).',
            'Edit the layout / zones and seat map as needed.',
            'Attach the hall to a ticket product when selling reserved seats.',
        ],
        'tips' => [
            'Keep hall names short — they appear on tickets and reports.',
        ],
        'shots' => [
            ['file' => 'events.jpg', 'caption' => 'Events page where hall tickets appear', 'url' => '/events'],
        ],
    ],

    'vote' => [
        'title' => 'Votes',
        'intro' => 'Monitor public votes, filter by payment status, add manual votes when needed, and clear history carefully.',
        'steps' => [
            'Use the status tabs: All · Successful · Pending · Failed (counts update automatically).',
            'Filter by date range, then submit.',
            'Successful votes are the ones that count on Vote Now.',
            'Pending usually means Mobile Money is waiting for confirmation — reconcile if a voter paid.',
            'Add Vote (permission required) for offline / complimentary votes.',
            'Clear Votes only after an agreed reset — it cannot be undone.',
        ],
        'tips' => [
            'Vote price and voting window are set under Settings → General Setting.',
            'Public Vote Now page: /contestants',
        ],
        'shots' => [
            ['file' => 'contestants.jpg', 'caption' => 'Vote Now (public ranking)', 'url' => '/contestants'],
            ['file' => 'vote-profile.jpg', 'caption' => 'Contestant profile — cast a vote', 'url' => '/contestants'],
        ],
        'videos' => [
            ['file' => 'how-to-vote.mp4', 'caption' => 'Silent guide: How to vote'],
        ],
    ],

    'point' => [
        'title' => 'Judge Grading',
        'intro' => 'Judges score contestants on fixed criteria. Totals feed Contestant Grading reports.',
        'steps' => [
            'Awaiting Candidate — see who still needs your score.',
            'Grade Candidate — select Judge + Candidate, enter points for each criterion, save.',
            'Accuracy (30) · Song choice (10) · Depth / spiritual impact (20) · Interpretation (20) · Overall presentation (20).',
            'The form shows a live Total — max is 100.',
            'Grade Listing — review or edit past scores.',
        ],
        'tips' => [
            'A judge can only grade a contestant once (already-rated names are hidden).',
            'Admins can grade on behalf of a judge by choosing the judge first.',
            'Criteria weights are configured under Grading → Grading Setting.',
        ],
        'shots' => [
            ['file' => 'grade-judge.jpg', 'caption' => 'Judge grading — enter criteria scores', 'url' => null],
            ['file' => 'candidates-sheet-1.jpg', 'caption' => 'All candidates — sheet 1', 'url' => '/contestants'],
            ['file' => 'candidates-sheet-2.jpg', 'caption' => 'All candidates — sheet 2', 'url' => '/contestants'],
        ],
        'videos' => [
            ['file' => 'how-to-grade.mp4', 'caption' => 'Silent guide: grade every candidate (Judge & Ambassador)'],
        ],
        'show_candidates' => true,
    ],

    'ambassador-point' => [
        'title' => 'Ambassador Grading',
        'intro' => 'How Ambassadors log in and grade contestants on mulemagc.com.',
        'steps' => [
            'Open the platform using the URL <strong>www.mulemagc.com</strong>.',
            'Click on <strong>Login</strong> (gold button in the header).',
            'Enter your username and password.',
            'Click on <strong>Login</strong>.',
            'When the OTP screen displays, enter the OTP sent to your phone (WhatsApp).',
            'Once you are logged in, click on <strong>Ambassador Grading</strong>.',
            'You will see <strong>3 tabs</strong>: Awaiting Grading · Grade Candidate · Grade Listing.',
            '<strong>1. Awaiting Grading</strong> — shows contestants that have not been graded yet. Tap a card to give points.',
            '<strong>2. Grade Candidate</strong> — manually select a candidate and give grades (max 5 points).',
            '<strong>3. Grade Listing</strong> — shows candidates and their grades.',
            'Use this Help tab anytime to move around the Ambassador portal.',
        ],
        'tips' => [
            'OTP is sent by WhatsApp to the phone number on your account. Ask admin if it never arrives.',
            'You grade each contestant only once. Awaiting Grading empties as you finish.',
            'Ambassador scores are separate from Judge scores.',
        ],
        'shots' => [
            ['file' => 'login.jpg', 'caption' => 'Login page', 'url' => '/login'],
            ['file' => 'grade-ambassador.jpg', 'caption' => 'Ambassador grading steps', 'url' => null],
            ['file' => 'candidates-sheet-1.jpg', 'caption' => 'All candidates — sheet 1', 'url' => '/contestants'],
            ['file' => 'candidates-sheet-2.jpg', 'caption' => 'All candidates — sheet 2', 'url' => '/contestants'],
        ],
        'videos' => [
            ['file' => 'how-to-grade.mp4', 'caption' => 'Silent guide: grade every candidate (Judge & Ambassador)'],
        ],
        'show_candidates' => true,
    ],

    'grading-setting' => [
        'title' => 'Grading',
        'intro' => 'Configure grading rules and review eliminations, qualified lists, and contestant ranking.',
        'steps' => [
            'Grading Setting — set criteria / weights used on judge & ambassador forms.',
            'Elimination list — contestants currently eliminated.',
            'Qualified Contestants — those who advanced.',
            'Contestant Grading — ranking from judge/ambassador points (and related totals).',
        ],
        'tips' => [
            'Public Green / Orange zones on Vote Now are set in Site Content → Eliminations for the week (vote totals), not this menu.',
        ],
        'shots' => [
            ['file' => 'contestants.jpg', 'caption' => 'Public Vote Now ranking', 'url' => '/contestants'],
            ['file' => 'candidates-sheet-1.jpg', 'caption' => 'All candidates — sheet 1', 'url' => '/contestants'],
            ['file' => 'candidates-sheet-2.jpg', 'caption' => 'All candidates — sheet 2', 'url' => '/contestants'],
        ],
        'videos' => [
            ['file' => 'how-to-grade.mp4', 'caption' => 'Silent guide: grade every candidate (Judge & Ambassador)'],
        ],
        'show_candidates' => true,
    ],

    'coin' => [
        'title' => 'Coins',
        'intro' => 'Manage coin packages used in the voting / rewards economy (if enabled).',
        'steps' => [
            'Open Coins List to review packages.',
            'Create Coins to add a new package (name, amount, status).',
            'Edit or deactivate packages that should no longer be sold.',
        ],
        'tips' => [
            'Keep coin values aligned with the current vote price in General Setting.',
        ],
        'shots' => [
            ['file' => 'home.jpg', 'caption' => 'Public site', 'url' => '/'],
        ],
    ],

    'expense' => [
        'title' => 'Expenses',
        'intro' => 'Track show and operations spending by category.',
        'steps' => [
            'Create Expense Categories first (e.g. Venue, Transport, Media).',
            'Add expenses with amount, date, category, and notes.',
            'Review lists and pull Income & Expenses from Reports when needed.',
        ],
        'tips' => [
            'Use clear notes — finance reports group by category.',
        ],
        'shots' => [],
    ],

    'people' => [
        'title' => 'People',
        'intro' => 'Manage staff users, admins, judges, ambassadors, and voter accounts.',
        'steps' => [
            'User List / Add User — create staff or role-based accounts.',
            'Admin — privileged dashboard users.',
            'Judges / Ambassadors — profiles used for grading and the public About pages.',
            'Voters — accounts that have participated in voting.',
            'Assign the correct Role so permissions match their job.',
        ],
        'tips' => [
            'Role permissions are under Settings → Role Permission.',
            'Public Login for staff is the gold Login button on the site header.',
        ],
        'shots' => [
            ['file' => 'login.jpg', 'caption' => 'Staff login page', 'url' => '/login'],
            ['file' => 'about.jpg', 'caption' => 'About Us (judges / story)', 'url' => '/about'],
        ],
    ],

    'contestants' => [
        'title' => 'Contestants',
        'intro' => 'Approve and maintain contestant profiles that appear on Vote Now.',
        'steps' => [
            'Contestants — full list; add or edit name, photo, department, contact, bio.',
            'Pending Contestants — review new applications before they go public.',
            'Qualified / Contestant Grading — jump to ranking reports.',
            'Ensure each approved contestant has a clear photo (used on cards and vote pages).',
        ],
        'tips' => [
            'Public URLs: /contestants and /contestant/{id}.',
            'Green & Orange zones are configured in Site Content → Eliminations for the week.',
        ],
        'shots' => [
            ['file' => 'contestants.jpg', 'caption' => 'Vote Now contestant grid', 'url' => '/contestants'],
            ['file' => 'vote-profile.jpg', 'caption' => 'Contestant profile', 'url' => '/contestants'],
            ['file' => 'candidates-sheet-1.jpg', 'caption' => 'All candidates — sheet 1', 'url' => '/contestants'],
            ['file' => 'candidates-sheet-2.jpg', 'caption' => 'All candidates — sheet 2', 'url' => '/contestants'],
        ],
        'show_candidates' => true,
    ],

    'account' => [
        'title' => 'Accounting',
        'intro' => 'Chart of accounts and departments used for financial tracking.',
        'steps' => [
            'Account List — create income/expense accounts as needed.',
            'Department — organisational units (also used for contestants).',
            'Add Account via the modal when you have permission.',
        ],
        'tips' => [
            'Departments often map to contest categories or production teams.',
        ],
        'shots' => [],
    ],

    'report' => [
        'title' => 'Reports',
        'intro' => 'Analytics hub for voting, tickets, contestants, and income/expense.',
        'steps' => [
            'Start at Reports Centre for the full menu of reports.',
            'Voting Report / Votes by Region — analyse public voting.',
            'Total Ticket Sales — revenue and ticket counts.',
            'Contestants List / Income and Expenses — exports and summaries.',
        ],
        'tips' => [
            'Use date filters before exporting for weekly board packs.',
        ],
        'shots' => [
            ['file' => 'contestants.jpg', 'caption' => 'Public vote totals feed voting reports', 'url' => '/contestants'],
        ],
    ],

    'site-content' => [
        'title' => 'Site Content',
        'intro' => 'Control what the public sees: homepage sections, popup, zones, Rate Us, gallery, menus, and more.',
        'steps' => [
            'Sections — turn homepage blocks on/off.',
            'Popup — flyer image shown once on visit.',
            'Most Voted & Hero — highlight top contestants.',
            'Eliminations for the week — Green Zone (top) and Orange Zone (bottom N) blink on Vote Now.',
            'Rate Us — enable the page and moderate reviews.',
            'Casting / Primes / Gallery / About / Judges / Ambassadors / Logos — content for those pages.',
            'Landing Menu & Side Menu — reorder public and admin menus (drag handles).',
        ],
        'tips' => [
            'Always Save after changing a tab.',
            'Zone blink is faster when a search match hits that card.',
        ],
        'shots' => [
            ['file' => 'home.jpg', 'caption' => 'Homepage', 'url' => '/'],
            ['file' => 'rate-us.jpg', 'caption' => 'Rate Us', 'url' => '/rate-us'],
            ['file' => 'contestants.jpg', 'caption' => 'Zones appear on Vote Now', 'url' => '/contestants'],
        ],
    ],

    'announcement' => [
        'title' => 'Announcements',
        'intro' => 'Compose and send WhatsApp announcements (and schedules) to voters, contestants, or custom lists.',
        'steps' => [
            'Create an announcement — write the message, pick audience, attach media if needed.',
            'Use templates for recurring weekly updates.',
            'Schedule or send immediately; track Sent history.',
            'Download PDF / Excel where available for records.',
        ],
        'tips' => [
            'Double-check the audience before bulk send.',
            'Phone numbers should be in international format for WhatsApp delivery.',
        ],
        'shots' => [
            ['file' => 'home.jpg', 'caption' => 'Brand context for announcement copy', 'url' => '/'],
        ],
    ],

    'setting' => [
        'title' => 'Settings',
        'intro' => 'System configuration: roles, general/vote settings, currency, brands, warehouses, and the full Help guide.',
        'steps' => [
            'Role Permission — control who can open each menu.',
            'General Setting — site title, logo, and vote-related options.',
            'Currency / Brand / Unit / Warehouse — catalogue basics for products.',
            'User Profile — update your own account.',
            'Help (last item) — full tabbed user guide for the whole platform.',
        ],
        'tips' => [
            'Module Help tabs (this panel) explain the menu you are in; Settings → Help is the complete guide.',
        ],
        'shots' => [
            ['file' => 'login.jpg', 'caption' => 'Login page', 'url' => '/login'],
            ['file' => 'home.jpg', 'caption' => 'Public site after settings apply', 'url' => '/'],
        ],
    ],

];
