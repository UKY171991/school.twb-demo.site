<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Create Entity</h2>
            <p class="text-sm font-medium text-slate-400 mt-1">Register for Institutional Access</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Full Identity</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe"
                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-700">
                @error('name') <p class="text-[9px] font-bold text-rose-500 mt-1.5 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Email Coordinates</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com"
                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-700">
                @error('email') <p class="text-[9px] font-bold text-rose-500 mt-1.5 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Account Class</label>
                <select id="role" name="role" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-bold text-slate-700">
                    <option value="">Select Access Level...</option>
                    <option value="student">Student Scholar</option>
                    <option value="teacher">Faculty Member</option>
                    <option value="admin">Institutional Administrator</option>
                </select>
                @error('role') <p class="text-[9px] font-bold text-rose-500 mt-1.5 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Passkey</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                           class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-700">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Confirm</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-700">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-4 mt-4 shadow-xl shadow-indigo-100/50">
                Establish Protocol
            </button>
        </form>

        <div class="pt-6 border-t border-slate-100 text-center">
             <p class="text-[11px] font-bold text-slate-400">
                Existing entity? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-black uppercase tracking-widest ml-1 hover:underline">Sign In Protocol</a>
            </p>
        </div>
    </div>
</x-guest-layout>
