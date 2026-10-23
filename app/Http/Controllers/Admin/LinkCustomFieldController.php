<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaveLinkCustomFieldRequest;
use App\Models\LinkCustomField;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LinkCustomFieldController extends Controller
{
    public function index(): View
    {
        return view('cp.custom-fields.index', [
            'title' => __('custom_fields.title'),
            'fields' => LinkCustomField::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('cp.custom-fields.form', [
            'title' => __('custom_fields.create_title'),
            'field' => new LinkCustomField(['type' => 'text', 'is_active' => true, 'is_required' => false, 'is_public' => false, 'sort_order' => 0]),
        ]);
    }

    public function store(SaveLinkCustomFieldRequest $request): RedirectResponse
    {
        LinkCustomField::create($request->fieldAttributes());

        return redirect()->route('cp.custom-fields.index')->with('success', __('custom_fields.created'));
    }

    public function edit(LinkCustomField $customField): View
    {
        return view('cp.custom-fields.form', [
            'title' => __('custom_fields.edit_title'),
            'field' => $customField,
        ]);
    }

    public function update(SaveLinkCustomFieldRequest $request, LinkCustomField $customField): RedirectResponse
    {
        $customField->update($request->fieldAttributes());

        return redirect()->route('cp.custom-fields.index')->with('success', __('custom_fields.updated'));
    }

    public function destroy(LinkCustomField $customField): RedirectResponse
    {
        $customField->delete();

        return redirect()->route('cp.custom-fields.index')->with('success', __('custom_fields.deleted'));
    }
}
