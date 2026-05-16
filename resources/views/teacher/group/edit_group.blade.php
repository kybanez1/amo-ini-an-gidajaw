@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-group-edit.css') }}">
@endpush

@section('content')
<div class="wrap">

    <div class="card">

        <div class="header">✏️ Edit Group</div>

        <div class="body">

            <form method="POST" action="{{ route('teacher.groups.update', $group->id) }}">
                @csrf
                @method('PUT')

                <!-- GROUP NAME -->
                <div class="field">
                    <label>Group Name</label>
                    <input type="text" name="name" value="{{ $group->name }}" required>
                </div>

                <!-- DESCRIPTION -->
                <div class="field">
                    <label>Description</label>
                    <textarea name="description" rows="3" required>{{ $group->description }}</textarea>
                </div>

                <!-- STATUS -->
                <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="active" {{ $group->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $group->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- STUDENTS (if you want to assign users) -->
                <div class="field">
                    <label>Students</label>
                    <select name="students[]" multiple>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ $group->students->contains($student->id) ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">Update Group</button>

            </form>

        </div>

    </div>

</div>
@endsection