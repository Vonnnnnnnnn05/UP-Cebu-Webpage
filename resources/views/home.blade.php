@extends('layouts.app')

@section('title', 'UP Cebu TTBDO - Technology Transfer & Business Development')

@section('content')
    <!-- Section 1: Navigation Header -->
    @include('partials.header')

    <!-- Section 2: Main Content -->
    <main>
        <!-- Hero Section -->
        @include('partials.hero')

        <!-- News & Announcements -->
        @include('partials.news')

        <!-- Programs & Services -->
        @include('partials.programs')

        <!-- Calendar & Events -->
        @include('partials.events')

        <!-- About Us -->
        @include('partials.about')
    </main>

    <!-- Section 3: Contact & Footer -->
    @include('partials.contact')
@endsection
