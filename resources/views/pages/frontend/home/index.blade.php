@extends('layouts.frontend.app')

@section('content')
  @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  @endpush

  @push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
          duration: 1000,
          once: true,
        });
      });
    </script>
  @endpush

  <!-- Hero Section -->
  <section class="flex items-center justify-center min-h-screen text-center transition-colors bg-gray-50 dark:bg-gray-900" data-aos="fade-up">
      <div class="max-w-6xl px-6 mx-auto">
          <h1 class="text-2xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-4xl lg:text-6xl dark:text-white">
              Landing Page
          </h1>
          <p class="max-w-3xl mx-auto mt-6 text-base text-gray-500 sm:text-lg dark:text-gray-400">
              Ini landing page
          </p>
      </div>
  </section>
@endsection
