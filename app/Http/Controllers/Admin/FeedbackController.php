<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\FeedbackRepository;
use Illuminate\Contracts\View\View;

class FeedbackController extends Controller
{
    public function __construct(private readonly FeedbackRepository $feedback)
    {
        parent::__construct();
    }

    /**
     * Показывает список сообщений, отправленных через форму обратной связи.
     */
    public function index(): View
    {
        return view('cp.feedback.index')->with('title', __('interface.admin.feedback'));
    }

    /**
     * Показывает детальную страницу сообщения обратной связи.
     */
    public function show(int $id): View
    {
        $feedbackMessage = $this->feedback->find($id);
        abort_if(! $feedbackMessage, 404);

        return view('cp.feedback.show', compact('feedbackMessage'))->with('title', __('interface.admin.feedback_messages.show_title'));
    }
}
