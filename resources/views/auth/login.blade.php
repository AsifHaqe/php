<x-guest-layout>
    <main class="auth-page">
        <section class="auth-shell" aria-label="Sign in to One Health">
            <aside class="auth-visual">
                <a class="auth-brand" href="{{ route('home') }}" aria-label="One Health home">
                    <span class="auth-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M17 7h6v10h10v6H23v10h-6V23H7v-6h10V7Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span>One<span class="auth-brand-accent">Health</span></span>
                </a>

                <div class="auth-visual-copy">
                    <span class="auth-eyebrow">YOUR HEALTH, IN GOOD HANDS</span>
                    <h1>Care that moves<br>with <span>you.</span></h1>
                    <p>One place to connect with your care team and keep your health journey on track.</p>
                </div>

                <div class="auth-art" aria-hidden="true">
                    <div class="auth-orbit auth-orbit-one"></div>
                    <div class="auth-orbit auth-orbit-two"></div>
                    <div class="auth-glass-card">
                        <div class="auth-glass-top">
                            <span class="auth-art-dot"></span>
                            <span>CARE OVERVIEW</span>
                            <span class="auth-art-menu">•••</span>
                        </div>
                        <div class="auth-art-cross">
                            <svg viewBox="0 0 80 80" fill="none">
                                <path d="M33 12h14v21h21v14H47v21H33V47H12V33h21V12Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="auth-art-lines">
                            <span></span><span></span>
                        </div>
                        <div class="auth-art-status"><span></span> Your care, connected</div>
                    </div>
                    <div class="auth-float-badge">
                        <span class="auth-badge-icon">+</span>
                        <span><strong>Here for you</strong><small>Every step of the way</small></span>
                    </div>
                    <div class="auth-orb"></div>
                </div>

                <p class="auth-visual-foot">A healthier tomorrow starts here.</p>
            </aside>

            <div class="auth-form-side">
                <div class="auth-mobile-brand">
                    <span class="auth-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M17 7h6v10h10v6H23v10h-6V23H7v-6h10V7Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span>One<span class="auth-brand-accent">Health</span></span>
                </div>

                <div class="auth-form-content">
                    <span class="auth-form-kicker">WELCOME BACK</span>
                    <h2>Sign in to your account</h2>
                    <p class="auth-form-intro">Your care is just a moment away.</p>

                    <x-validation-errors class="auth-errors" />

                    @session('status')
                        <div class="auth-status" role="status">{{ $value }}</div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="auth-form">
                        @csrf

                        <div class="auth-field">
                            <label for="email">{{ __('Email address') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                        </div>

                        <div class="auth-field">
                            <div class="auth-label-row">
                                <label for="password">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                                @endif
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        </div>

                        <label for="remember_me" class="auth-remember">
                            <input id="remember_me" type="checkbox" name="remember" @checked(old('remember'))>
                            <span>{{ __('Remember me') }}</span>
                        </label>

                        <button class="auth-submit" type="submit">
                            <span>{{ __('Log in') }}</span>
                            <span class="auth-submit-arrow" aria-hidden="true">→</span>
                        </button>
                    </form>

                    @if (Route::has('register'))
                        <p class="auth-register-prompt">
                            New to One Health?
                            <a href="{{ route('register') }}">{{ __('Create an account') }}</a>
                        </p>
                    @endif
                </div>

                <p class="auth-form-foot">Secure access to your personal health space.</p>
            </div>
        </section>
    </main>
</x-guest-layout>
