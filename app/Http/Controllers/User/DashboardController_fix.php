// Line 52-60 এ এই code replace করুন:
        // Announcements
        $announcements = Announcement::where('is_active', true)
            ->where(function($q) {
                $q->where('target', 'all')
                  ->orWhere('target', 'users');
            })
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
