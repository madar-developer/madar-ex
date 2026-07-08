<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Support\NotificationMessage;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:setting_show', ['only' => ['index', 'edit', 'update', 'sync']]);
    }

    public function index(Request $request)
    {
        $title = 'رسائل التنبيهات و SMS';
        if (NotificationTemplate::count() === 0) {
            NotificationMessage::syncDefaults();
        }

        $query = NotificationTemplate::query()->orderBy('category')->orderBy('channel')->orderBy('key');

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->get('channel'));
        }

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('key', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            });
        }

        $templates = $query->paginate(30);
        $search = $request->all();

        return view('admin.notification-templates.index', compact('title', 'templates', 'search'));
    }

    public function edit($id)
    {
        $title = 'تعديل رسالة';
        $template = NotificationTemplate::findOrFail($id);

        return view('admin.notification-templates.edit', compact('title', 'template'));
    }

    public function update(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
            'placeholders' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active');

        $template->update($data);

        \Illuminate\Support\Facades\Cache::forget('notification_templates');

        return redirect('/dashboard/notification-templates')->with('success', 'تم الحفظ بنجاح');
    }

    public function sync()
    {
        $count = NotificationMessage::syncDefaults();

        return redirect('/dashboard/notification-templates')->with('success', "تم مزامنة {$count} رسالة");
    }
}
