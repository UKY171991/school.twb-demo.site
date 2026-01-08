<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Welcome Back</h2>
            <p class="text-sm font-medium text-slate-400 mt-1">Institutional Login Protocol</p>
        </div>

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-600 text-xs font-bold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Email Coordinates</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@example.com"
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-700">
                </div>
                @error('email') <p class="text-[10px] font-bold text-rose-500 mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Secure Passkey</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-700">
                </div>
            </div>

            <div class="flex items-center justify-between px-1">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg bg-slate-50 border-slate-200 text-indigo-600 focus:ring-indigo-500/20">
                    <span class="ml-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Persist Session</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:text-indigo-600 transition-colors">Reset Key?</a>
                @endif
            </div>

            <button type="submit" class="btn-primary w-full py-4 shadow-xl shadow-indigo-100/50">
                Initialize Access
            </button>
        </form>

        <div class="pt-6 border-t border-slate-100 text-center">
             <p class="text-[11px] font-bold text-slate-400">
                New entity? 
                <a href="{{ route('register') }}" class="text-indigo-600 font-black uppercase tracking-widest ml-1 hover:underline">Create Account</a>
            </p>
        </div>

        <!-- Demo Accounts Info (Optional but helpful for testing) -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Debug Credentials</h4>
            <div class="grid grid-cols-2 gap-2 text-[10px] font-bold text-slate-600">
                <div class="bg-white p-2 rounded-xl border border-slate-100">admin@example.com</div>
                <div class="bg-white p-2 rounded-xl border border-slate-100">teacher1@example.com</div>
            </div>
            <p class="text-[8px] font-black text-slate-400 mt-2 uppercase tracking-tighter">Default PassKey: password</p>
        </div>
    </div>
</x-guest-layout>
