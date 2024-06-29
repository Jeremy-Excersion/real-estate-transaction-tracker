<script setup></script>

<template>
  <div>
    <header class="py-10">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="relative z-50 flex justify-between">
          <div class="flex items-center md:gap-x-12">
            <a href="/" class="flex items-center">
              <span class="ml-2 text-xl font-semibold text-slate-900">
                Real Estate Sales Tracker
              </span>
            </a>
            <div class="hidden md:flex md:gap-x-6">
              <a
                class="inline-block rounded-lg px-2 py-1 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                href="#features"
              >
                Features
              </a>
              <a
                class="inline-block rounded-lg px-2 py-1 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                href="#testimonials"
              >
                Testimonials
              </a>
              <a
                class="inline-block rounded-lg px-2 py-1 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                href="#pricing"
              >
                Pricing
              </a>
            </div>
          </div>
          <div class="flex items-center gap-x-5 md:gap-x-8">
            <div class="hidden md:block">
              <a
                class="inline-block rounded-lg px-2 py-1 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                href="/login"
              >
                Sign in
              </a>
            </div>
            <a
              class="group inline-flex items-center justify-center rounded-full py-2 px-4 text-sm font-semibold focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 bg-blue-600 text-white hover:text-slate-100 hover:bg-blue-500 active:bg-blue-800 active:text-blue-100 focus-visible:outline-blue-600"
              color="blue"
              variant="solid"
              href="/register"
            >
              <span>
                Get started
                <span class="hidden lg:inline">today</span>
              </span>
            </a>
            <div class="-mr-1 md:hidden">
              <div data-headlessui-state="">
                <button
                  class="relative z-10 flex h-8 w-8 items-center justify-center ui-not-focus-visible:outline-none"
                  aria-label="Toggle Navigation"
                  type="button"
                  aria-expanded="false"
                  data-headlessui-state=""
                  id="headlessui-popover-button-:r2:"
                >
                  <svg
                    aria-hidden="true"
                    class="h-3.5 w-3.5 overflow-visible stroke-slate-700"
                    fill="none"
                    stroke-width="2"
                    stroke-linecap="round"
                  >
                    <path d="M0 1H14M0 7H14M0 13H14" class="origin-center transition"></path>
                    <path
                      d="M2 2L12 12M12 2L2 12"
                      class="origin-center transition scale-90 opacity-0"
                    ></path>
                  </svg>
                </button>
              </div>
              <div
                hidden=""
                style="
                  position: fixed;
                  top: 1px;
                  left: 1px;
                  width: 1px;
                  height: 0px;
                  padding: 0px;
                  margin: -1px;
                  overflow: hidden;
                  clip: rect(0px, 0px, 0px, 0px);
                  white-space: nowrap;
                  border-width: 0px;
                  display: none;
                "
              ></div>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <main>
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16 pt-20 text-center lg:pt-32">
        <h1
          class="mx-auto max-w-4xl font-display text-5xl font-medium tracking-tight text-slate-900 sm:text-7xl"
        >
          Real Estate Bookkeeping
          <span class="relative whitespace-nowrap text-blue-600">
            <svg
              aria-hidden="true"
              viewBox="0 0 418 42"
              class="absolute left-0 top-2/3 h-[0.58em] w-full fill-blue-300/70"
              preserveAspectRatio="none"
            >
              <path
                d="M203.371.916c-26.013-2.078-76.686 1.963-124.73 9.946L67.3 12.749C35.421 18.062 18.2 21.766 6.004 25.934 1.244 27.561.828 27.778.874 28.61c.07 1.214.828 1.121 9.595-1.176 9.072-2.377 17.15-3.92 39.246-7.496C123.565 7.986 157.869 4.492 195.942 5.046c7.461.108 19.25 1.696 19.17 2.582-.107 1.183-7.874 4.31-25.75 10.366-21.992 7.45-35.43 12.534-36.701 13.884-2.173 2.308-.202 4.407 4.442 4.734 2.654.187 3.263.157 15.593-.78 35.401-2.686 57.944-3.488 88.365-3.143 46.327.526 75.721 2.23 130.788 7.584 19.787 1.924 20.814 1.98 24.557 1.332l.066-.011c1.201-.203 1.53-1.825.399-2.335-2.911-1.31-4.893-1.604-22.048-3.261-57.509-5.556-87.871-7.36-132.059-7.842-23.239-.254-33.617-.116-50.627.674-11.629.54-42.371 2.494-46.696 2.967-2.359.259 8.133-3.625 26.504-9.81 23.239-7.825 27.934-10.149 28.304-14.005.417-4.348-3.529-6-16.878-7.066Z"
              ></path>
            </svg>
            <span class="relative">made simple</span>
          </span>
          for small businesses.
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg tracking-tight text-slate-700">
          Most bookkeeping software is accurate, but hard to use. We make it easy to keep your books
          up to date, so you can focus on running your business.
        </p>
      </div>
    </main>
  </div>
</template>
