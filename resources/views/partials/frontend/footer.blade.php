@php
    $footerContent = json_decode($footer->content ?? '{}', true);
@endphp

<!-- Footer Section -->
<footer class="py-12 text-gray-800 transition-colors bg-white border-t border-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700">
  <!-- Bottom Copyright -->
  <div class="flex flex-col items-center justify-between max-w-6xl px-6 mx-auto mt-8 text-gray-500 dark:text-gray-400 md:flex-row">
    <!-- Left: Copyright -->
    <div class="text-center md:text-left">
      © 2025 Lana Septiana. All rights reserved.
    </div>

    <!-- Right: Made with Love -->
    <div class="flex items-center mt-2 space-x-2 md:mt-0">
      <span>Made with</span>
      <i class="text-xl text-red-500 bx bxs-heart dark:text-red-400"></i>
      <span>by Lana Septiana</span>
    </div>
  </div>
</footer>
