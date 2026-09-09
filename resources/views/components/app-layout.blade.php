@extends('layouts.admin')

@section('title', $title ?? 'Account')

@section('header')
    {!! $header ?? 'Account' !!}
@endsection

@section('content')
    {{ $slot }}
@endsection
