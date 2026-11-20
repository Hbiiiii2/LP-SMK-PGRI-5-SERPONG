<?php

namespace App\Controllers\Admin;

use App\Models\NewsModel;
use App\Models\ContactMessageModel;
use App\Models\NewsCommentModel;
use App\Models\TeacherModel;
use App\Models\ProgramModel;
use App\Models\EventModel;
use App\Models\AchievementModel;
use App\Models\ExtracurricularModel;
use App\Models\GalleryModel;
use App\Models\FaqModel;
use App\Models\SpmbModel;
use App\Models\UserModel;
use Throwable;

class Dashboard extends BaseController
{
    /**
     * Display the admin dashboard with summary statistics and recent data.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Dashboard';

        // Cache for 120 seconds covering 15 dashboard queries (acceptable staleness for stats).
        try {
            $stats = cache()->remember('admin_dashboard_stats', 120, static function () {
                return [
                    'total_news' => new NewsModel()->countAll(),
                    'total_events' => new EventModel()->countAll(),
                    'total_teachers' => new TeacherModel()->countAll(),
                    'total_programs' => new ProgramModel()->countAll(),
                    'total_messages' => new ContactMessageModel()->getUnreadCount(),
                    'total_comments' => new NewsCommentModel()->getPendingCount(),
                    'total_achievements' => new AchievementModel()->countAll(),
                    'total_extracurriculars' => new ExtracurricularModel()->countAll(),
                    'total_galleries' => new GalleryModel()->countAll(),
                    'total_faqs' => new FaqModel()->countAll(),
                    'total_users' => new UserModel()->countAll(),
                    'total_spmb' => new SpmbModel()->countAll(),
                    'pending_spmb' => new SpmbModel()->getPendingCount(),
                ];
            });

            $recent = cache()->remember('admin_dashboard_recent', 120, static function () {
                return [
                    'recent_news' => new NewsModel()->getRecent(),
                    'upcoming_events' => new EventModel()->getUpcoming(5),
                    'recent_messages' => new ContactMessageModel()->getRecent(),
                    'pending_comments' => new NewsCommentModel()->getPendingRecent(),
                    'recent_spmb' => new SpmbModel()->getRecent(5),
                ];
            });

            $this->data = array_merge($this->data, $stats, $recent);
        } catch (Throwable) {
            // Fallback without cache when the cache backend fails.
            $this->data['total_news'] = new NewsModel()->countAll();
            $this->data['total_events'] = new EventModel()->countAll();
            $this->data['total_teachers'] = new TeacherModel()->countAll();
            $this->data['total_programs'] = new ProgramModel()->countAll();
            $this->data['total_messages'] = new ContactMessageModel()->getUnreadCount();
            $this->data['total_comments'] = new NewsCommentModel()->getPendingCount();
            $this->data['total_achievements'] = new AchievementModel()->countAll();
            $this->data['total_extracurriculars'] = new ExtracurricularModel()->countAll();
            $this->data['total_galleries'] = new GalleryModel()->countAll();
            $this->data['total_faqs'] = new FaqModel()->countAll();
            $this->data['total_users'] = new UserModel()->countAll();
            $this->data['total_spmb'] = new SpmbModel()->countAll();
            $this->data['pending_spmb'] = new SpmbModel()->getPendingCount();
            $this->data['recent_news'] = new NewsModel()->getRecent();
            $this->data['upcoming_events'] = new EventModel()->getUpcoming(5);
            $this->data['recent_messages'] = new ContactMessageModel()->getRecent();
            $this->data['pending_comments'] = new NewsCommentModel()->getPendingRecent();
            $this->data['recent_spmb'] = new SpmbModel()->getRecent(5);
        }

        return view('admin/dashboard', $this->data);
    }
}
