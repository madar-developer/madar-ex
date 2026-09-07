<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMessageTemplateRequest;
use App\Http\Requests\Admin\UpdateMessageTemplateRequest;
use App\Models\Company;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:notifications_show');
    }

    public function index(Request $request)
    {
        $title = 'قوالب رسائل الشركات';
        $query = MessageTemplate::query()->with('companies')->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('company_id')) {
            $query->whereHas('companies', function ($q) use ($request) {
                $q->where('companies.id', $request->get('company_id'));
            });
        }
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            });
        }

        $templates = $query->paginate(30);
        $search = $request->all();
        $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();

        return view('admin.message-templates.index', compact('title', 'templates', 'search', 'companies'));
    }

    public function create()
    {
        $title = 'إضافة قالب رسالة';
        $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();

        return view('admin.message-templates.add', compact('title', 'companies'));
    }

    public function store(StoreMessageTemplateRequest $request)
    {
        $template = MessageTemplate::create([
            'name' => $request->name,
            'status' => $request->status,
            'body' => $request->body,
            'active' => $request->has('active') ? 1 : 0,
        ]);
        $template->companies()->sync($request->company_ids);

        return redirect('/dashboard/message-templates')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit($id)
    {
        $template = MessageTemplate::with('companies')->findOrFail($id);
        $title = 'تعديل قالب رسالة';
        $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();

        return view('admin.message-templates.edit', compact('title', 'template', 'companies'));
    }

    public function update(UpdateMessageTemplateRequest $request, $id)
    {
        $template = MessageTemplate::findOrFail($id);
        $template->update([
            'name' => $request->name,
            'status' => $request->status,
            'body' => $request->body,
            'active' => $request->has('active') ? 1 : 0,
        ]);
        $template->companies()->sync($request->company_ids);

        return redirect('/dashboard/message-templates')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy(MessageTemplate $message_template)
    {
        $message_template->companies()->detach();
        $message_template->delete();

        return 'success';
    }
}
