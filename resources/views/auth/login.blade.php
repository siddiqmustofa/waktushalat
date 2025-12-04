<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif
    <div class="text-center mb-4">
        <i class="ni ni-watch-time text-primary" style="font-size: 2rem;"></i>
        <h4 class="mt-2 mb-0">Jam Digital Shalat Masjid</h4>
        <p class="text-muted mb-0">Masuk untuk mengelola</p>
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">Ingat saya</label>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="text-sm text-primary" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
            <button type="submit" class="btn btn-primary">Masuk</button>
        </div>
    </form>
</x-guest-layout>
