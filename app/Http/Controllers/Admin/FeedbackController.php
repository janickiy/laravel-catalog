<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;

class FeedbackController extends Controller
{
    /**
     * Показывает список сообщений, отправленных через форму обратной связи.
     */
    public function __invoke(): View
    {
        return view('cp.feedback.index')->with('title', 'Сообщения с сайта');
    }
}
