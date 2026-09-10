<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Saint Francis of Assisi College — Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <style>
    :root{--sfac-bg:#F8F7F5;--sfac-crimson:#8B1A1A;--sfac-gold:#D4A017}
    html,body{height:100%}
    body{font-family:'DM Sans',system-ui,Segoe UI,Arial,sans-serif;background-color:var(--sfac-bg)}
    .login-bg{background-image:url('https://stfrancisbacoor.com/assets/img/bac.jpg');background-size:cover;background-position:center}
    .font-display{font-family:'DM Serif Display',serif}
  </style>
</head>
<body class="min-h-screen relative overflow-hidden login-bg">
  <div class="absolute inset-0 bg-slate-950/65"></div>
  <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10">
    <div class="grid gap-12 lg:grid-cols-[1.05fr_0.95fr] items-center max-w-6xl w-full">
      <div class="flex flex-col items-center justify-center gap-6 text-center px-4 sm:px-0">
        <div class="mx-auto h-24 w-24 rounded-3xl bg-white/90 flex items-center justify-center text-[--sfac-crimson] shadow-lg border border-white/20">
          <i class="fa-solid fa-dove text-3xl"></i>
        </div>
        <div class="space-y-3 max-w-xl">
          <h1 class="font-display text-4xl tracking-tight text-white">Saint Francis of Assisi College</h1>
          <p class="text-sm text-slate-200">Central Dashboard Portal</p>
        </div>
      </div>

      <div class="w-full max-w-md mx-auto bg-white/95 border border-white/20 shadow-2xl rounded-[2rem] p-8 backdrop-blur-sm">
        <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
      @csrf

      @if(session('status'))
        <div class="text-sm text-crimson-600 bg-red-50 border border-crimson-100 p-2 rounded">{{ session('status') }}</div>
      @endif

      <div>
        <label for="username" class="block text-xs font-medium text-slate-500">Username or Email</label>
        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus placeholder="admin@example.test or admin" class="mt-1 block w-full border border-slate-200/70 rounded-xl px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-[--sfac-gold] focus:border-[--sfac-gold]" />
        @error('username')
            <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password" class="block text-xs font-medium text-slate-500">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password" class="mt-1 block w-full border border-slate-200/70 rounded-xl px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-[--sfac-gold] focus:border-[--sfac-gold]" />
        @error('password')
            <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center justify-between text-sm text-slate-500">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="remember" class="h-4 w-4 text-[--sfac-crimson] border-slate-300 rounded" />
          Remember me
        </label>
        <a href="{{ route('password.request') }}" class="text-slate-600 hover:text-[--sfac-crimson]">Forgot?</a>
      </div>

      <div>
        <button type="submit" class="w-full bg-[--sfac-crimson] hover:bg-[#7a1818] text-white font-semibold px-4 py-3 rounded-xl">Log In</button>
      </div>

      <p class="text-xs text-gray-500 mt-3">Seeded test accounts: admin@example.test, staff@example.test, student@example.test (password: <strong>password</strong>)</p>
    </form>
  </div>
</body>
</html>
