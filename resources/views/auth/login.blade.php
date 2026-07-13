<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Login - Toko Buah Mas Ali</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: { extend: {} },
            plugins: [],
        }
    </script>
</head>
<body class="m-0 p-0 bg-[#f9f9ff] font-sans antialiased">
<div class="flex items-center justify-center min-h-screen w-full px-4 box-border">
    <div class="w-full max-w-sm bg-white rounded-2xl border border-[#c2c6d4] shadow-sm p-8 box-border">

        <div class="text-center mb-8">
            <p class="m-0 font-bold text-[#003f87] text-2xl leading-8">Toko Buah Mas Ali</p>
            <p class="m-0 text-[#424752] text-sm mt-1 opacity-80">Silakan masuk untuk melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 px-4 py-3 rounded-lg bg-[#fdecec] border border-[#f3b6b6] text-[#ba1a1a] text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="flex flex-col gap-4">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[#191c21] text-sm font-semibold">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="nama@email.com"
                    class="w-full bg-white rounded-lg border border-[#c2c6d4] py-2.5 px-4 text-gray-700 text-sm focus:outline-none focus:border-[#0056b3] box-border"
                />
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-[#191c21] text-sm font-semibold">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                    class="w-full bg-white rounded-lg border border-[#c2c6d4] py-2.5 px-4 text-gray-700 text-sm focus:outline-none focus:border-[#0056b3] box-border"
                />
            </div>

            <label class="flex items-center gap-2 text-[#424752] text-sm select-none">
                <input type="checkbox" name="remember" class="rounded border-[#c2c6d4]" />
                Ingat saya
            </label>

            <button
                type="submit"
                class="w-full mt-2 bg-[#0056b3] hover:bg-[#003f87] text-white font-bold text-sm py-2.5 rounded-lg border-0 cursor-pointer transition-colors"
            >
                Masuk
            </button>
        </form>
    </div>
</div>
</body>
</html>
