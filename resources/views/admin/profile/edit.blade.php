@extends('layouts.admin')

@section('title', 'Profile')
@section('header', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
  <div class="card-base p-6">
    @include('profile.partials.update-profile-information-form')
  </div>
  <div class="card-base p-6">
    @include('profile.partials.update-password-form')
  </div>
  <div class="card-base p-6">
    <p class="text-sm text-neutral-600 mb-4">Manage two-factor authentication for admin access.</p>
    <a href="{{ route('2fa.setup') }}" class="btn-secondary px-4 py-2 text-sm inline-flex">Two-factor settings</a>
  </div>
  <div class="card-base p-6">
    @include('profile.partials.delete-user-form')
  </div>
</div>
@endsection
