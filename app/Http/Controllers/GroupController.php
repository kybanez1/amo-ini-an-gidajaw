<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * GROUP LIST
     */
    public function index(): View
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('groups')) {
            return view('teacher.group.index_group', [
                'groups' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12),
            ]);
        }

        $groups = Group::with(['teacher', 'students', 'projects'])
            ->where('teacher_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('teacher.group.index_group', compact('groups'));
    }

    /**
     * CREATE GROUP PAGE
     */
    public function create(): View
    {
        // Only show students who entered this teacher's code
        $students = auth()->user()->myStudents()
            ->orderBy('name')
            ->get();

        return view('teacher.group.create_group', compact('students'));
    }

    /**
     * STORE GROUP
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'students'    => 'nullable|array',
            'students.*'  => 'exists:users,id',
        ], [
            'name.required' => 'Group name is required.',
            'name.max'      => 'Group name must be under 255 characters.',
        ]);

        try {

            $group = Group::create([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'teacher_id'  => auth()->id(),
                'status'      => 'active',
                // join_code is auto-generated in Group::booted()
            ]);

            if (!empty($validated['students'])) {
                // Teacher manually adds students - is_joined=0 until student enters code
                $ids = collect($validated['students'])->mapWithKeys(fn($id) => [$id => ['is_joined' => 0]])->all();
                $group->students()->attach($ids);
            }

            return redirect()
                ->route('teacher.groups.show', $group->id)
                ->with('success', 'Group "' . $group->name . '" created! Share the join code with students: ' . $group->join_code);

        } catch (\Exception $e) {

            return redirect()
                ->route('teacher.groups.index')
                ->withInput()
                ->withErrors(['name' => 'Failed to create group. Please try again.']);
        }
    }

    /**
     * SHOW GROUP
     */
    public function show(Group $group): View
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $group->load(['teacher', 'students', 'projects']);

        $students = $group->students()->orderBy('name')->paginate(10);

        // Only show teacher's registered students not already in this group
        $availableStudents = auth()->user()->myStudents()
            ->whereNotIn('users.id', $group->students->pluck('id'))
            ->orderBy('name')
            ->get();

        return view(
            'teacher.group.show',
            compact('group', 'students', 'availableStudents')
        );
    }

    /**
     * EDIT GROUP
     */
    public function edit(Group $group): View
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only show students who entered this teacher's code
        $students = auth()->user()->myStudents()
            ->orderBy('name')
            ->get();

        return view('teacher.group.edit_group', compact('group', 'students'));
    }

    /**
     * UPDATE GROUP
     */
    public function update(Request $request, Group $group): RedirectResponse
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
            'students'    => 'nullable|array',
            'students.*'  => 'exists:users,id',
        ]);

        $group->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status'      => $validated['status'] ?? $group->status,
        ]);

        $ids = collect($validated['students'] ?? [])->mapWithKeys(fn($id) => [$id => ['is_joined' => 0]])->all();
        $group->students()->sync($ids);

        return redirect()
            ->route('teacher.groups.index')
            ->with('success', 'Group updated successfully!');
    }

    /**
     * DELETE GROUP
     */
    public function destroy(Group $group): RedirectResponse
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $group->students()->detach();
        $group->delete();

        return redirect()
            ->route('teacher.groups.index')
            ->with('success', 'Group deleted successfully!');
    }

    /**
     * ADD STUDENT
     */
    public function addStudent(Request $request, Group $group): RedirectResponse
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate(['student_id' => 'required|exists:users,id']);

        $group->students()->syncWithoutDetaching([$request->student_id => ['is_joined' => 0]]);

        return back()->with('success', 'Student added successfully!');
    }

    /**
     * REMOVE STUDENT
     */
    public function removeStudent(Group $group, User $student): RedirectResponse
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $group->students()->detach($student->id);

        return back()->with('success', 'Student removed successfully!');
    }

    /**
     * REGENERATE JOIN CODE
     */
    public function regenerateCode(Group $group): RedirectResponse
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $newCode = $group->regenerateCode();

        return back()->with('success', 'New join code generated: ' . $newCode);
    }
}