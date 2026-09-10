<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Reset Password — SFAC Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <style>
    :root{--sfac-bg:#F8F7F5;--sfac-crimson:#8B1A1A;--sfac-gold:#D4A017}
    html,body{height:100%}
    body{font-family:'DM Sans',system-ui,Segoe UI,Arial,sans-serif;background-color:var(--sfac-bg)}
    .font-display{font-family:'DM Serif Display',serif}
  </style>
</head>
<body class="min-h-screen relative overflow-hidden">
  <div class="absolute inset-0 bg-slate-950/65"></div>
  <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10">
    <div class="w-full max-w-lg bg-white/95 border border-white/20 shadow-2xl rounded-[2rem] p-8 backdrop-blur-sm">
      <div class="mb-6 text-center">
        <h1 class="font-display text-3xl text-slate-900">Reset Password</h1>
        <p class="text-sm text-slate-500">Enter your new password to continue.</p>
      </div>

      @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="space-y-5" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
          <label for="email" class="block text-xs font-medium text-slate-500">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-1 block w-full border border-slate-200/70 rounded-xl px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-[--sfac-gold] focus:border-[--sfac-gold]" />
          @error('email')
            <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-xs font-medium text-slate-500">New Password</label>
          <input id="password" name="password" type="password" required class="mt-1 block w-full border border-slate-200/70 rounded-xl px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-[--sfac-gold] focus:border-[--sfac-gold]" />
          @error('password')
            <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-xs font-medium text-slate-500">Confirm Password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full border border-slate-200/70 rounded-xl px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-[--sfac-gold] focus:border-[--sfac-gold]" />
        </div>

        <div class="flex flex-col gap-3">
          <button type="submit" class="w-full bg-[--sfac-crimson] hover:bg-[#7a1818] text-white font-semibold px-4 py-3 rounded-xl">Reset password</button>
          <a href="{{ route('login') }}" class="text-center text-sm text-slate-600 hover:text-[--sfac-crimson]">Back to login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
