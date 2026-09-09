<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CircularSend;
use Illuminate\Http\Request;

class CircularSendController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:setting_show');
    }

    public function index(Request $request)
    {
        $title = 'سجل إرسال التعاميم';
        $query = CircularSend::query()
            ->with('admin')
            ->withCount(['companyRecipients', 'driverRecipients', 'adminRecipients'])
            ->latest();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $sends = $query->paginate(30);
        $search = $request->all();

        return view('admin.circular-sends.index', compact('title', 'sends', 'search'));
    }

    public function show($id)
    {
        $send = CircularSend::with(['admin', 'recipients'])->findOrFail($id);
        $title = 'تفاصيل إرسال تعميم';

        $companies = $send->recipients->where('recipient_type', \App\Models\CircularSendRecipient::TYPE_COMPANY);
        $drivers = $send->recipients->where('recipient_type', \App\Models\CircularSendRecipient::TYPE_DRIVER);
        $admins = $send->recipients->where('recipient_type', \App\Models\CircularSendRecipient::TYPE_ADMIN);

        return view('admin.circular-sends.show', compact('title', 'send', 'companies', 'drivers', 'admins'));
    }
}
